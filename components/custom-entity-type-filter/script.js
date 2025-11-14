app.component('custom-entity-type-filter', {
    template: $TEMPLATES['custom-entity-type-filter'],

    setup() {
        const text = Utils.getTexts('custom-entity-type-filter');
        return { text };
    },

    props: {
        types: {
            type: Array,
            default: () => [],
        },
        pseudoQuery: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            items: [],
        };
    },

    watch: {
        types: {
            immediate: true,
            handler: 'normalizeTypes',
        },
    },

    computed: {
        hasItems() {
            return this.items.length > 0;
        },
    },

    methods: {
        normalizeTypes() {
            if (!Array.isArray(this.types)) {
                this.items = [];
                this.validatePseudoQueryType();
                return;
            }

            this.items = this.types
                .map((item) => this.normalizeType(item))
                .filter((item) => item !== null);

            this.validatePseudoQueryType();
        },

        normalizeType(item) {
            if (!item || typeof item != 'object') {
                return null;
            }

            const value = this.normalizeValue(item.value ?? item.id ?? null);
            const label = this.normalizeLabel(item.label ?? item.name ?? null);

            if (value == null || label == null) {
                return null;
            }

            return { value, label };
        },

        normalizeValue(value) {
            if (typeof value == 'number') {
                return value;
            }

            if (typeof value == 'string') {
                const trimmed = value.trim();

                if (!trimmed.length) {
                    return null;
                }

                const numeric = Number(trimmed);
                return Number.isNaN(numeric) ? trimmed : numeric;
            }

            return null;
        },

        normalizeLabel(label) {
            if (typeof label == 'string') {
                const trimmed = label.trim();
                return trimmed.length ? trimmed : null;
            }

            if (typeof label == 'number') {
                return String(label);
            }

            return null;
        },

        validatePseudoQueryType() {
            if (!this.pseudoQuery) {
                return;
            }

            const allowedValues = this.items.map((item) => item.value);

            if (
                this.pseudoQuery.type != undefined &&
                !allowedValues.includes(this.pseudoQuery.type)
            ) {
                this.pseudoQuery.type = undefined;
            }
        },
    },
});

