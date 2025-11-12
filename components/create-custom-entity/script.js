app.component('create-custom-entity', {
    template: $TEMPLATES['create-custom-entity'],
    emits: ['create'],

    setup() {
        // os textos estão localizados no arquivo texts.php deste componente 
        const text = Utils.getTexts('create-custom-entity')
        return { text }
    },

    created() {
        this.iterationFields();
        var stat = 'publish';
    },

    data() {
        return {
            entity: null,
            fields: [],
        }
    },

    props: {
        editable: {
            type: Boolean,
            default: true
        },

        type: {
            type: String,
            required: true
        }
    },

    computed: {
        areaErrors() {
            return this.entity.__validationErrors['term-area'];
        },
        areaClasses() {
            return this.areaErrors ? 'field error' : 'field';
        },
        modalTitle() {
            if (this.entity?.id) {
                if (this.entity.status == 1) {
                    return __('entidade criada', 'create-custom-entity');
                } else {
                    return __('criar rascunho', 'create-custom-entity');
                }
            } else {
                return __('criar entidade', 'create-custom-entity');

            }
        },
    },

    methods: {
        iterationFields() {
            let skip = [
                'createTimestamp',
                'id',
                'location',
                'name',
                'shortDescription',
                'status',
                'type',
                '_type',
                'userId',
            ];
            Object.keys($DESCRIPTIONS[this.type]).forEach((item) => {
                if (!skip.includes(item) && $DESCRIPTIONS[this.type][item].required) {
                    this.fields.push(item);
                }
            })
        },
        createEntity() {
            this.entity = Vue.ref(new Entity(this.type));
            this.entity.type = 1;
            this.entity.terms = { area: [] }
        },
        createDraft(modal) {
            this.entity.status = 0;
            this.save(modal);
        },
        createPublic(modal) {
            //lançar dois eventos
            this.entity.status = 1;
            this.save(modal);
        },
        save(modal) {
            modal.loading(true);
            this.entity.save().then((response) => {
                this.$emit('create', response)
                modal.loading(false);
                Utils.pushEntityToList(this.entity);

            }).catch((e) => {
                modal.loading(false);
            });
        },

        destroyEntity() {
            // para o conteúdo da modal não sumir antes dela fechar
            setTimeout(() => this.entity = null, 200);
        }
    },
});
