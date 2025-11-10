app.component('search-filter-custom-entity', {
    template: $TEMPLATES['search-filter-custom-entity'],

    setup() {
        // os textos estão localizados no arquivo texts.php deste componente
        const text = Utils.getTexts('search-filter-custom-entity');
        return { text };
    },

    emits: ['clear'],

    props: {
        position: {
            type: String,
            default: 'list',
        },
        pseudoQuery: {
            type: Object,
            required: true,
        },
        slug: {
            type: String
        }
    },

    methods: {
        clearFilters() {
            const types = ['string', 'boolean'];
            for (const key in this.pseudoQuery) {
                if (Array.isArray(this.pseudoQuery[key])) {
                    this.pseudoQuery[key] = [];
                } else if (types.includes(typeof this.pseudoQuery[key])) {
                    delete this.pseudoQuery[key];
                }
            }            
        },
    },
});

