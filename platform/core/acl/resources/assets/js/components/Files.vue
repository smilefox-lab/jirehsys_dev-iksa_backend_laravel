

<template>
    <div class="d-flex flex-column justify-content-start align-items-center">
        <label for="file[]" class="cursor-pointer">
            <input name="file[]" type="file" id="file[]" multiple accept="application/*" @change="onFileChange">
            <div>Agregar Archivos</div>
        </label>
        <section class="container-preview" :class="{ 'container-preview-alone': isShow }">
            <div v-for="(file, key) in draft" :key="`files${key}`" class="d-flex flex-row justify-content-start align-items-center preview">
                <img src="/vendor/core/images/files.svg" />
                <div class="file-name">{{ fileName(file) }}</div>
                <div class="download-files" @click="download(file)"><i class="fa fa-download"></i></div>
                <div class="destroy-files" @click="destroy(key)"><i class="fa fa-trash"></i></div>
                <input type="hidden" name="filesInput[]" :value="file">
            </div>
            <div v-for="(file, key) in newFiles" :key="`newFiles${key}`" class="d-flex flex-row justify-content-start align-items-center preview">
                <img src="/vendor/core/images/files.svg" />
                <div class="file-name">{{ file.name }}</div>
            </div>
        </section>
    </div>
</template>

<script>
    import {HalfCircleSpinner} from 'epic-spinners'

    export default {
        components: {
            HalfCircleSpinner
        },
        props: ['files'],
        data() {
            return {
                isLoading: true,
                draft: [],
                newFiles: [],
                selectedFiles: null
            };
        },
        mounted() {
            this.draft = JSON.parse(this.files)
        },
        computed: {
            isShow() {
                return !this.draft?.length > 0 && !this.newFiles.length > 0;
            },
        },
        methods: {
            onFileChange(e) {
                this.selectedFiles = Array.from(e.target.files);
                if (this.newFiles.length > 0) {
                    this.newFiles = [];
                }

                for (let i = 0; i < this.selectedFiles.length; i++) {
                    this.newFiles.push(this.selectedFiles[i]);
                }
            },
            destroy(index) {
                this.draft.splice(index, 1);
            },
            forceFileDownload(buffer, name) {
                const url = window.URL.createObjectURL(new Blob([buffer]))
                const link = document.createElement('a')
                link.href = url
                link.setAttribute('download', name)
                document.body.appendChild(link)
                link.click()
            },
            download(fileUrl) {
                this.isLoading = true;
                let urlDownload = `${window.location.origin}/admin/downloads/${fileUrl}`;
                urlDownload = urlDownload.replace('#', '%23');
                
                axios.get(encodeURI(urlDownload), { responseType: 'blob' })
                    .then(res =>  {
                        if (res.data) {
                            this.forceFileDownload(res.data, this.fileName(fileUrl));
                        }
                        this.isLoading = false;
                    })
                    .catch(res =>  {
                        Botble.handleError(res);
                        this.isLoading = false;
                    });
            },
            fileName(file) {
                const name = file.split('/');
                return name[name.length - 1];
            }
        }
    }
</script>

<style lang="scss" scoped>
    input[type="file"] {
        display: none;
    }

    label {
        position: absolute;
        top: 0;
        right: 0;
        line-height: 35px;
        height: 35px;
        margin-right: 10px;
        right: 0;
        font-weight: 600;
        color: #febc2c;
    }

    .container-preview {
        width: 100%;
        display: flex;
        flex-flow: column;
        justify-content: flex-start;

        .preview {
            position: relative;
            margin-bottom: 0.5em;

            img {
                width: 20px;
                margin-right: 0.5em;
            }

            .file-name {
                width: 100%;
                overflow-wrap: break-word;
                overflow: hidden;
            }

            .destroy-files {
                cursor: pointer;
                width: 20px;
                color: #fd413c;
                margin-left: 1.4em;
                &:hover {
                    opacity: 0.9;
                }
            }

            .download-files {
                cursor: pointer;
                width: 20px;
                color: #febc2c;
                margin-left: 1.4em;
                &:hover {
                    opacity: 0.9;
                }
            }
        }
    }

    .container-preview-alone {
        justify-content: center !important;
    }


</style>
