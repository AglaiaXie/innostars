<template>
  <b-field class="file">
    <b-upload v-model="files">
      <a class="button is-primary">
        <b-icon icon="upload"></b-icon>
        <span>{{ label }}</span>
      </a>
    </b-upload>
    <span class="file-name" v-if="files.length || model">
       {{ files.length ? files[0].name : model.filename }}
    </span>
    <span v-if="files.length">
       <button class="button is-info" @click="uploadFile()">
         <b-icon icon="save"></b-icon>
         <span>Save</span>
       </button>
    </span>
    <span v-if="model">
      <a class="button is-warning" :href="'/file/' + model.disk_name">
        <b-icon icon="external-link"></b-icon>
          <span>Download</span>
      </a>
      <button class="button is-danger" @click="deleteFile()">
        <b-icon icon="trash"></b-icon>
        <span>Delete</span>
      </button>
    </span>
  </b-field>
</template>

<script>
    export default {
        props: ['label', 'model', 'upload_event', 'delete_event', 'property_key'],
        data() {
            return {
                files: []
            }
        },
        methods: {
            uploadFile() {
                var instance = this;
                var formData = new FormData();
                instance.loadingComponment = instance.$loading.open();
                formData.append('file', instance.files[0]);
                return axios.post(
                    '/api/v1/files',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(function (response) {
                    instance.$toast.open({
                        message: 'File uploaded successfully!',
                        type: 'is-success'
                    });
                    instance.loadingComponment.close();
                    instance.files = [];
                    bus.$emit(instance.upload_event, instance.property_key, response.data);
                }).catch(function (error) {
                    instance.loadingComponment.close();
                    var message = 'Upload ' + instance.files[0].filename + ' failed, server error';
                    if (error.response.status === 413) {
                        message = 'Upload failed, ' + instance.files[0].filename + ' too large';
                    }
                    instance.$toast.open({
                        message: message,
                        type: 'is-danger'
                    });
                });
            },
            deleteFile() {
                const instance = this;
                instance.$dialog.confirm({
                    title: 'Delete file',
                    message: 'Are you sure that you want to delete this file?',
                    confirmText: 'Delete',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: function () {
                        axios.delete('/api/v1/files/' + instance.model.id)
                            .then(function () {
                                instance.$toast.open({
                                    message: 'File deleted!',
                                    type: 'is-success'
                                });
                                bus.$emit(instance.delete_event, instance.property_key);
                            })
                            .catch(function () {
                                instance.$toast.open({
                                    message: 'Error, unable to delete file',
                                    type: 'is-danger',
                                    duration: 10000
                                });
                            });
                    }
                })
            }
        }
    }
</script>