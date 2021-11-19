<template>
    <div class="row">
        <div style="margin: auto; width:30px;" v-if="isLoading">
            <half-circle-spinner
                :animation-duration="1000"
                :size="15"
                color="#808080"
            />
        </div>
        <div class="col-md-3 col-sm-6 container-grid" v-for="item in data" :key="item.id" v-if="!isLoading && data.length">
            <div class="grid-in">
                <div class="grid-shadow">
                    <div class="hourseitem" style="margin-top: 0; ">
                        <div class="blii">
                            <div class="img"><img style="border-radius: 0" class="thumb" :data-src="item.image" :src="item.image" :alt="item.name">
                            </div>
                            <a :href="item.url" class="linkdetail"></a>
                        </div>
                    </div>
                    <div class="grid-h">
                        <div class="blog-title">
                            <a :href="item.url">
                                <h2>{{ item.name }}</h2></a>
                        </div>
                        <div class="blog-excerpt">
                            <p>{{ item.description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {HalfCircleSpinner} from 'epic-spinners'

    export default {
        components: {
            HalfCircleSpinner
        },

        data: function() {
            return {
                isLoading: true,
                data: []
            };
        },

        mounted() {
            this.getProperties();
        },

        props: {
            type: {
                type: String,
                default: () => '',
            },
            url: {
                type: String,
                default: () => null,
                required: true
            },
        },

        methods: {
            getProperties() {
                this.data = [];
                this.isLoading = true;
                axios.get(this.url)
                    .then((res) => {
                        this.data = res.data.data;
                        this.isLoading = false;
                    });
            },
        }
    }
</script>
