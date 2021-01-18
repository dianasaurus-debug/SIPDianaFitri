<template>
    <div>
        <div class="col-12 mt-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Konser</h3>

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
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Seats</th>
                            <th>Tanggal</th>
                            <th>Durasi (Menit)</th>
                            <th>Tempat</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="concert in concerts.data" :key="concert.id">
                            <td><input type="checkbox" v-model="deleteItems" :value="`${concert.id}`"></td>
                            <td>{{ concert.judul }}</td>
                            <td>{{ concert.idol }}</td>
                            <td>{{ concert.jumlah_seat }}</td>
                            <td>{{ concert.tanggal }}</td>
                            <td>{{ concert.duration }}</td>
                            <td>{{ concert.tempat }}</td>
                            <td>
                                <a href="#" class="mr-2" @click="viewConcert(concert.id)">
                                    <i class="fas fa-eye" style="color : red !important;"></i>
                                </a>
                                <a href="#" class="mr-2" @click="editModal(concert)">
                                    <i class="fas fa-user-edit" style="color : lightseagreen !important;"></i>
                                </a>
                                <a href="#" @click="deleteConcert(concert.id)">
                                    <i class="fas fa-trash" style="color : red !important;"></i>
                                </a>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <pagination :data="concerts" @pagination-change-page="getAllConcerts"></pagination>
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
                        <h5 class="modal-title" v-show="!editmode">Tambah Konser</h5>
                        <h5 class="modal-title" v-show="editmode">Edit Konser</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="color : red">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateConcert() : createConcert()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.judul" type="text" name="judul"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('judul') }"
                                       placeholder="Judul Konser">
                                <has-error :form="form" field="judul"></has-error>
                            </div>
                            <div class="form-group">
                                <textarea v-model="form.tempat" type="text" name="tempat"
                                          class="form-control" :class="{ 'is-invalid': form.errors.has('tempat') }"
                                          placeholder="Tempat Konser..."></textarea>
                                <has-error :form="form" field="tempat"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.tanggal" type="date" name="tanggal"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('tanggal') }"
                                       placeholder="Tanggal">
                                <has-error :form="form" field="tanggal"></has-error>
                            </div>
                            <div class="form-group">
                                <select class="form-control" id="choose_major" name="idol_id" v-model="form.idol_id">
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    <option v-for="category in categories.data" v-bind:value="category.id">{{ category.nama_idol }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input v-model="form.jumlah_seat" type="number" name="jumlah_seat"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('jumlah_seat') }"
                                       placeholder="Jumlah Seat">
                                <has-error :form="form" field="jumlah_seat"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.duration" type="number" name="duration"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('duration') }"
                                       placeholder="Durasi (Menit)">
                                <has-error :form="form" field="duration"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.price" type="number" name="price"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('price') }"
                                       placeholder="Harga (IDR)">
                                <has-error :form="form" field="price"></has-error>
                            </div>
                            <div class="form-group">
                                <img id="diplay_img" v-bind:src="imagePreview" style="border-radius : 5px;max-height : 120px; max-width: 130px;"/><hr/>
                                <input type="file" name="gambar" class="form-control-file" id="picture" @change="onFileChange">
                                <has-error :form="form" field="gambar"></has-error>
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
            <div class="modal-dialog modal-lg modal-dialog-centered">
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
                            <div class="col-12">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">Judul</th>
                                        <th scope="col">Tempat</th>
                                        <th scope="col">Tanggal</th>
                                        <th scope="col">Seat</th>
                                        <th scope="col">Durasi</th>
                                        <th scope="col">Kategori</th>
                                        <th scope="col">Harga (IDR)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th>{{concert.judul}}</th>
                                        <td>{{concert.tempat}}</td>
                                        <td>{{concert.tanggal}}</td>
                                        <td>{{concert.jumlah_seat}}</td>
                                        <td>{{concert.duration}}</td>
                                        <td>{{concert.idol}}</td>
                                        <td>{{ concert.price }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                            <div class="row" v-show="loading == false">
                                <div class="col-12">
                                    <img class="img-thumbnail w-100" :src="'/images/concerts/'+concert.gambar">
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
            concerts: {},
            categories : {},
            concert : {},
            failed : false,
            loading : false,
            deleteItems: [],
            select: '',
            all_select: false,
            form: new Form({
                id: '',
                judul: '',
                tempat: '',
                idol_id: '',
                jumlah_seat: '',
                gambar : '',
                tanggal : '',
                duration : '',
                price : '',
            }),
            imagePreview: '/images/image_placeholder.png',
        }
    },
    methods: {
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
                    axios.post('/concerts/multiple-delete/'+this.deleteItems).then(() => {
                        Fire.$emit('AfterCreated');
                        this.deleteItems = [];
                        this.all_select == true ?
                            this.all_select = false : this.all_select = true;
                        swal.fire(
                            'Deleted!',
                            'Data konser sudah dihapus',
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
                this.concerts.data.forEach(category => {
                    this.deleteItems.push(category.id)
                });
            }else{
                this.all_select = false
                this.deleteItems = []
            }
        },
        newModal() {
            this.imagePreview = '/images/image_placeholder.png';
            this.form.reset();
            this.editmode = false;
            $("#addNew").modal('show');
        },
        editModal(concert) {
            this.form.reset();
            this.editmode = true;
            this.form.fill(concert);
            this.imagePreview = '/images/concerts/'+concert.gambar;
            $("#addNew").modal('show');
        },
        getIdols(){
            axios.get('/idols').then(categories=> {
                this.categories = categories.data;
            })

        },
        viewConcert(id){
            this.loading = true;
            axios.get('/concerts/'+id).then( response => {
                this.concert = response.data;
                this.failed = false;
            }). catch((error) => {
                this.failed = true;
            }). finally(()=> {
                this.loading = false;
            });
            $("#viewData").modal('show');
        },
        createConcert() {
            const config = {
                headers: { 'content-type': 'multipart/form-data' }
            }
            let formData = new FormData();

            formData.append("judul", this.form.judul);
            formData.append("tempat", this.form.tempat);
            formData.append("tanggal", this.form.tanggal);
            formData.append("idol_id", this.form.idol_id);
            formData.append("duration", this.form.duration);
            formData.append("jumlah_seat", this.form.jumlah_seat);
            formData.append("price", this.form.price);
            formData.append("gambar", this.form.gambar);

            this.$Progress.start();
            axios.post('/concerts', formData, config).then( () => {
                Fire.$emit('AfterCreated');
                $("#addNew").modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                toast.fire({
                    icon: 'success',
                    title: 'Concert has been successfully added!'
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
        getAllConcerts(page) {
            let uri = '/concerts?page=' + page;
            axios.get(uri).then(response => {
                return response.data;
            }).then(data => {
                this.concerts = data;
            });
        },
        deleteConcert(id) {
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
                    this.form.delete('/concerts/' + id).then(() => {
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
        updateConcert() {
            this.$Progress.start();
            const config = {
                headers: { 'content-type': 'multipart/form-data' }
            }
            let formData = new FormData();
            formData.append("judul", this.form.judul);
            formData.append("tempat", this.form.tempat);
            formData.append("tanggal", this.form.tanggal);
            formData.append("idol_id", this.form.idol_id);
            formData.append("duration", this.form.duration);
            formData.append("jumlah_seat", this.form.jumlah_seat);
            formData.append("gambar", this.form.gambar);
            formData.append("price", this.form.price);
            formData.append("_method", 'PATCH');
            axios.post('/concerts/' + this.form.id, formData, config).then(() => {
                Fire.$emit('AfterCreated');
                $("#addNew").modal('hide');
                swal.fire(
                    'Updated!',
                    'Data concert sudah diupdate',
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
            this.form.gambar = event.target.files[0];

            let reader  = new FileReader();

            reader.addEventListener("load", function () {
                this.imagePreview = reader.result;
            }.bind(this), false);

            if( this.form.gambar ){
                if ( /\.(jpe?g|jpg|png|gif)$/i.test( this.form.gambar.name ) ) {

                    console.log("here");
                    reader.readAsDataURL( this.form.gambar );
                }
            }
        },
    },
    created() {
        this.getIdols();
        this.getAllConcerts();
        Fire.$on('AfterCreated', () => {
            this.getAllConcerts();
        })
    },
    mounted() {
        console.log('Component mounted.')
    }
}
</script>
