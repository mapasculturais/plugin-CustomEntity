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
            if (Array.isArray(rawTerms)) {
                return rawTerms.map((term) => this.normalizeTerm(term)).filter(Boolean);
            }

            if (rawTerms && typeof rawTerms == 'object') {
                return Object.values(rawTerms)
                    .map((term) => this.normalizeTerm(term))
                    .filter(Boolean);
            }

            return [];
        },

        normalizeTerm(term) {
            if (typeof term === 'string') {
                return term;
            }

            if (term && typeof term === 'object') {
                if (typeof term.label === 'string') {
                    return term.label;
                }

                if (typeof term.name === 'string') {
                    return term.name;
                }

                if (typeof term.description === 'string') {
                    return term.description;
                }
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
        },
    },
});


