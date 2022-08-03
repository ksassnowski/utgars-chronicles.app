<script>
    export default {
        name: 'ConfirmAction',

        props: {
            timeout: {
                type: Number,
                default: 3000,
            },
        },

        data() {
            return {
                pressed: false,
            };
        },

        methods: {
            act() {
                if (this.pressed) {
                    this.$emit('confirmed');
                    return;
                }

                this.pressed = true;

                setTimeout(() => {
                    this.pressed = false;
                }, this.timeout);
            },
        },

        render() {
            return this.$scopedSlots.default({
                act: this.act,
                needsConfirmation: this.pressed,
            });
        },
    };
</script>

