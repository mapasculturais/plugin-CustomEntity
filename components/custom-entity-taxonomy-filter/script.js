app.component('custom-entity-taxonomy-filter', {
    template: $TEMPLATES['custom-entity-taxonomy-filter'],

    setup() {
        const text = Utils.getTexts('custom-entity-taxonomy-filter');
        return { text };
    },

    props: {
        taxonomy: {
            type: Object,
            default: () => ({}),
        },
        pseudoQuery: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            item: null,
        };
    },

    watch: {
        taxonomy: {
            immediate: true,
            handler: 'normalizeTaxonomy',
        },
    },

    methods: {
        normalizeTaxonomy() {
            if (!this.taxonomy || typeof this.taxonomy !== 'object') {
                this.item = null;
                return;
            }

            const slug = typeof this.taxonomy.slug === 'string' ? this.taxonomy.slug : null;
            const description = typeof this.taxonomy.description === 'string'
                ? this.taxonomy.description
                : (typeof this.taxonomy.name === 'string' ? this.taxonomy.name : null);

            if (!slug || !description) {
                this.item = null;
                return;
            }

            const terms = this.normalizeTerms(
                this.taxonomy.terms ?? this.taxonomy.restrictedTerms ?? []
            );

            this.item = {
                slug,
                description,
                terms,
            };

            this.initializePseudoQuery();
        },

        normalizeTerms(rawTerms) {
            const normalized = {};

            if (Array.isArray(rawTerms)) {
                rawTerms.forEach((term) => {
                    const { value, label } = this.normalizeTerm(term);

                    if (value && label) {
                        normalized[value] = label;
                    }
                });

                return normalized;
            }

            if (rawTerms && typeof rawTerms === 'object') {
                Object.entries(rawTerms).forEach(([key, term]) => {
                    const { value, label } = this.normalizeTerm(term, key);

                    if (value && label) {
                        normalized[value] = label;
                    }
                });

                return normalized;
            }

            return normalized;
        },

        normalizeTerm(term, fallbackValue = null) {
            if (typeof term === 'string' || typeof term === 'number') {
                const normalized = this.normalizeTermString(term);

                if (normalized) {
                    return { value: normalized, label: normalized };
                }

                return { value: null, label: null };
            }

            if (term && typeof term === 'object') {
                const value = this.normalizeTermString(
                    term.value ?? term.slug ?? term.key ?? term.id ?? fallbackValue
                );

                const labelSource = term.label ?? term.name ?? term.description ?? term.title ?? value;
                const label = this.normalizeTermString(labelSource);

                if (value && label) {
                    return { value, label };
                }

                if (value) {
                    return { value, label: value };
                }

                if (label) {
                    return { value: label, label };
                }
            }

            const normalizedFallback = this.normalizeTermString(fallbackValue);

            if (normalizedFallback) {
                return { value: normalizedFallback, label: normalizedFallback };
            }

            return { value: null, label: null };
        },

        normalizeTermString(value) {
            if (typeof value === 'string') {
                const trimmed = value.trim();
                return trimmed.length ? trimmed : null;
            }

            if (typeof value === 'number') {
                return String(value);
            }

            return null;
        },

        initializePseudoQuery() {
            if (!this.item) {
                return;
            }

            const key = `term:${this.item.slug}`;

            if (!Array.isArray(this.pseudoQuery[key])) {
                this.pseudoQuery[key] = [];
            }

            this.pseudoQuery[key] = this.pseudoQuery[key].filter(
                (value) => value && Object.prototype.hasOwnProperty.call(this.item.terms, value)
            );
        },
    },
});


