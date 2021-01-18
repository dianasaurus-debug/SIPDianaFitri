<template>
    <div>
        <div class="col-12 mt-3">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Kategori</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-danger" v-show="deleteItems.length > 0" @click="deleteMultipleCategories">
                            Delete Selected
                        </button>
                        <button type="button" class="btn btn-success" @click="newModal">
                            Tambah <i class="fas fa-user-plus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th>

                                <input type="checkbox" @click="select_all_via_check_box" v-model="all_select">

                                <span> {{ all_select == true ? 'Uncheck All' : "Select All" }} </span>
                            </th>

                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Foto</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="category in categories.data" :key="category.id">
                            <td><input type="checkbox" v-model="deleteItems" :value="`${category.id}`"></td>
                            <td>{{ category.nama_idol }}</td>
                            <td>{{ category.desc && category.desc.length > 8 ? category.desc.substring(0,50)+".." : category.desc  }}
                            </td>
                            <td>
                                <img :src="'/images/idols/'+category.foto" style="width: 70px;"/>
                            </td>
                            <td>
                                <a href="#" class="mr-2" @click="viewIdol(category.id)">
                                    <i class="fas fa-eye" style="color : red !important;"></i>
                                </a>
                                <a href="#" class="mr-2" @click="editModal(category)">
                                    <i class="fas fa-user-edit" style="color : lightseagreen !important;"></i>
                                </a>
                                <a href="#" @click="deleteIdol(category.id)">
                                    <i class="fas fa-trash" style="color : red !important;"></i>
                                </a>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <pagination :data="categories" @pagination-change-page="getAllIdols"></pagination>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- Modal Add|edit -->
        <div class="modal fade" id="addNew" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode">Tambah Kategori</h5>
                        <h5 class="modal-title" v-show="editmode">Edit Kategori</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="color : red">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateIdol() : createIdol()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.nama_idol" type="text" name="nama_idol"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('nama_idol') }"
                                       placeholder="Nama Kategori">
                                <has-error :form="form" field="nama_idol"></has-error>
                            </div>
                            <div class="form-group">
                                <textarea v-model="form.desc" type="text" name="desc"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('desc') }"
                                          placeholder="Deskripsi..."></textarea>
                                <has-error :form="form" field="desc"></has-error>
                            </div>
                            <div class="form-group">
                                    <img id="diplay_img" v-bind:src="imagePreview" style="border-radius : 5px;max-height : 120px; max-width: 130px;"/><hr/>
                                    <input type="file" name="foto" class="form-control-file" id="picture" @change="onFileChange">
                                    <has-error :form="form" field="foto"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal View -->
        <div class="modal fade" id="viewData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">View Kategori</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="color : red">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center"><pulse-loader :loading="loading" :color="color" :size="size"></pulse-loader></div>
                        <div class="row" v-show="loading == false">
                                    <div class="col-8">
                                        <h4>{{category.nama_idol}}</h4>
                                        <hr>
                                        <p class="text-align-justify">{{category.desc}}</p>
                                    </div>
                                    <div class="col-4">
                                        <img class="img-thumbnail w-100" :src="'/images/idols/'+category.foto">
                                    </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            color : 'green',
            size : '20px',
            editmode: false,
            categories: {},
            category : {},
            failed : false,
            loading : false,
            deleteItems: [],
            select: '',
            all_select: false,
            form: new Form({
                id: '',
                nama_idol: '',
                desc: '',
                foto: '',
            }),
            imagePreview: '/images/image_placeholder.png',
        }
    },
    methods: {
        newModal() {
            this.imagePreview = '/images/image_placeholder.png';
            this.form.reset();
            this.editmode = false;
            $("#addNew").modal('show');
        },
        editModal(category) {
            this.form.reset();
            this.editmode = true;
            this.form.fill(category);
            this.imagePreview = '/images/idols/'+category.foto;
            $("#addNew").modal('show');
        },
        viewIdol(id){
            this.loading = true;
            axios.get('/idols/'+id).then( response => {
                this.category = response.data;
                this.failed = false;
            }). catch((error) => {
                this.failed = true;
            }). finally(()=> {
                this.loading = false;
            });
            $("#viewData").modal('show');
        },
        createIdol() {
            const config = {
                headers: { 'content-type': 'multipart/form-data' }
            }
            let formData = new FormData();

            formData.append("nama_idol", this.form.nama_idol);
            formData.append("desc", this.form.desc);
            formData.append("foto", this.form.foto);

            this.$Progress.start();
            axios.post('/idols', formData, config).then( () => {
                Fire.$emit('AfterCreated');
                $("#addNew").modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                toast.fire({
                    icon: 'success',
                    title: 'Category has been successfully added!'
                })
                this.$Progress.finish();
            })
                .catch( () => {
                    this.$Progress.fail();
                    swal.fire(
                        'Not Created!',
                        'Pastikan anda mengisikan data dengan benar',
                        'error'
                    )
                });
        },
        getAllIdols(page) {
            let uri = '/idols?page=' + page;
            axios.get(uri).then(response => {
                return response.data;
            }).then(data => {
                this.categories = data;
            });
        },
        deleteIdol(id) {
            swal.fire({
                title: 'Anda yakin?',
                text: "Tindakan ini tidak bisa dibalikkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, hapus saja!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.form.delete('/idols/' + id).then(() => {
                        Fire.$emit('AfterCreated');
                        swal.fire(
                            'Deleted!',
                            'Data kategori sudah dihapus',
                            'success'
                        )
                    });
                }
            })
        },
        deleteMultipleCategories() {
            swal.fire({
                title: `${this.deleteItems.length} data akan dihapus`,
                text: "Tindakan ini tidak bisa dibalikkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, hapus saja!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/idols/multiple-delete/'+this.deleteItems).then(() => {
                        Fire.$emit('AfterCreated');
                        this.deleteItems = [];
                        this.all_select == true ?
                            this.all_select = false : this.all_select = true;
                        swal.fire(
                            'Deleted!',
                            'Data kategori sudah dihapus',
                            'success'
                        )
                    }).catch((error)=> {
                        console.log(this.deleteItems);
                        console.log('ada keasalahan');
                    });
                }
            })
        },
        select_all_via_check_box(){
            if(this.all_select == false){
                this.all_select = true
                this.categories.data.forEach(category => {
                    this.deleteItems.push(category.id)
                });
            }else{
                this.all_select = false
                this.deleteItems = []
            }
        },
        updateIdol() {
            this.$Progress.start();
            const config = {
                headers: { 'content-type': 'multipart/form-data' }
            }
            let formData = new FormData();
            formData.append("nama_idol", this.form.nama_idol);
            formData.append("desc", this.form.desc);
            formData.append("foto", this.form.foto);
            formData.append("_method", 'PATCH');
            axios.post('/idols/' + this.form.id, formData, config).then(() => {
                Fire.$emit('AfterCreated');
                $("#addNew").modal('hide');
                swal.fire(
                    'Updated!',
                    'Data category sudah diupdate',
                    'success'
                )
                this.$Progress.finish();
            })
                .catch(() => {
                    console.log(formData);
                    this.$Progress.fail();
                    swal.fire(
                        'Not Updated!',
                        'Pastikan anda mengisikan data dengan benar',
                        'error'
                    )
                });
        },
        onFileChange(event){
            this.form.foto = event.target.files[0];

            let reader  = new FileReader();

            reader.addEventListener("load", function () {
                this.imagePreview = reader.result;
            }.bind(this), false);

            if( this.form.foto ){
                if ( /\.(jpe?g|jpg|png|gif)$/i.test( this.form.foto.name ) ) {

                    console.log("here");
                    reader.readAsDataURL( this.form.foto );
                }
            }
        },
    },

    created() {
        this.getAllIdols();
        Fire.$on('AfterCreated', () => {
            this.getAllIdols();
        })
    },
    mounted() {
        console.log('Component mounted.')
    }
}
</script>
