<template>
    <div>
        <div class="col-12 mt-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Users</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-danger" v-show="deleteItems.length > 0" @click="deleteMultipleUsers">
                            Delete Selected
                        </button>

                        <select v-model="selected.role">
                            <option value="all" selected>All Role</option>
                            <option v-for="role in roles" v-bind:value="role.id">{{ role.nama }}</option>
                        </select>
                        <select v-model="selected.created_at">
                            <option value="all">All Dates</option>
                            <option value="7d">Last 7 days</option>
                            <option value="2d">Today</option>
                            <option value="yd">Yesterday</option>
                        </select>
                        <button type="button" class="btn btn-success" @click="newModal">
                            Tambah <i class="fas fa-user-plus"></i>
                        </button>
                        <a :href="'/export/excel?role='+this.selected.role+'&created_at='+this.selected.created_at"
                           class="btn btn-primary">Export Excel</a>
                        <input type="file" :class="{ ' is-invalid' : error.message }" id="input-file-import"
                               name="file_import" ref="import_file" @change="onFileChange">
                        <button class="btn btn-success" v-show="import_file != ''" @click="importExcel()">Import
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <h5 v-show="users.data.length==0" class="text-center mt-2">Belum ada data</h5>
                    <table class="table table-hover text-nowrap" v-show="users.data.length > 0">
                        <thead>
                        <tr>
                            <th>
                                <input type="checkbox" @click="select_all_via_check_box" v-model="all_select">
                                <span> {{ all_select == true ? 'Uncheck All' : "Select All" }} </span>
                            </th>
                            <th>Nama</th>
                            <th>E-Mail</th>
                            <th>No. HP</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Tanggal</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="user in users.data" :key="user.id" v-show="user.email!=authuser.email">
                            <td><input type="checkbox" v-model="deleteItems" :value="`${user.id}`"></td>
                            <td>{{ user.name }}</td>
                            <td>{{ user.email }}</td>
                            <td>{{ user.nomor_hp }}</td>
                            <td>{{ user.username }}</td>
                            <td>{{ user.role }}</td>
                            <td>{{ user.created_at }}</td>
                            <td>
                                <a href="#" class="mr-2" @click="viewUser(user.id)">
                                    <i class="fas fa-eye" style="color : red !important;"></i>
                                </a>
                                <a href="#" class="mr-2" @click="editModal(user)">
                                    <i class="fas fa-user-edit" style="color : lightseagreen !important;"></i>
                                </a>
                                <a href="#" @click="deleteUser(user.id)">
                                    <i class="fas fa-trash" style="color : red !important;"></i>
                                </a>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card -->
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode">Tambah User</h5>
                        <h5 class="modal-title" v-show="editmode">Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="color : red">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateUser() : createUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }"
                                       placeholder="Nama">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.email" type="email" name="email"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('email') }"
                                       placeholder="E-Mail">
                                <has-error :form="form" field="email"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.username" type="text" name="username"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('username') }"
                                       placeholder="Username">
                                <has-error :form="form" field="username"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.nomor_hp" type="text" name="nomor_hp"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('nomor_hp') }"
                                       placeholder="Nomor Telepon">
                                <has-error :form="form" field="nomor_hp"></has-error>
                            </div>
                            <div class="form-group">
                                <select class="form-control" id="choose_major" name="role_id" v-model="form.role_id">
                                    <option value="" disabled selected>Pilih Role</option>
                                    <option v-for="role in roles" v-bind:value="role.id">{{ role.nama }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input v-model="form.password" type="password" name="password"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('password') }"
                                       placeholder="Password">
                                <has-error :form="form" field="password"></has-error>
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

        <div class="modal fade" id="viewData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">View User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="color : red">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>E-Mail</th>
                                        <th>No. HP</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Tanggal</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{ user.name }}</td>
                                        <td>{{ user.email }}</td>
                                        <td>{{ user.nomor_hp }}</td>
                                        <td>{{ user.username }}</td>
                                        <td>{{ user.role }}</td>
                                        <td>{{ user.created_at }}</td>
                                    </tr>
                                    </tbody>
                                </table>
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
            users: {},
            editmode: false,
            roles: [],
            user : {},
            authuser: auth,
            deleteItems: [],
            select: '',
            all_select: false,
            form: new Form({
                id: '',
                name: '',
                email: '',
                username: '',
                nomor_hp: '',
                role_id: '',
                password: '',
            }),
            error: '',
            import_file: '',
            selected: {
                role: 'all',
                created_at: 'all',
            }
        }
    },
    watch: {
        selected: {
            handler: function () {
                this.getAllUsers();
            },
            deep: true
        }
    },
    methods: {
        viewUser(id){
            this.loading = true;
            axios.get('/users/'+id).then( response => {
                this.user = response.data;
                this.failed = false;
            }). catch((error) => {
                this.failed = true;
            }). finally(()=> {
                this.loading = false;
            });
            $("#viewData").modal('show');
        },
        deleteMultipleUsers() {
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
                    axios.post('/users/multiple-delete/'+this.deleteItems).then(() => {
                        Fire.$emit('AfterCreated');
                        this.deleteItems = [];
                        this.all_select == true ?
                            this.all_select = false : this.all_select = true;
                        swal.fire(
                            'Deleted!',
                            'Data users sudah dihapus',
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
                this.users.data.forEach(user => {
                    this.deleteItems.push(user.id)
                });
            }else{
                this.all_select = false
                this.deleteItems = []
            }
        },

        newModal() {
            this.form.reset();
            this.editmode = false;
            $("#addNew").modal('show');
        },
        editModal(user) {
            this.form.reset();
            this.editmode = true;
            this.form.fill(user);
            $("#addNew").modal('show');
        },
        getAllRoles() {
            axios.get('/roles').then(roles => {
                this.roles = roles.data;
            })
        },
        createUser() {
            this.$Progress.start();
            this.form.post('/users').then(() => {
                Fire.$emit('AfterCreated');
                $("#addNew").modal('hide');
                toast.fire({
                    icon: 'success',
                    title: 'User has been successfully added!'
                })
                this.$Progress.finish();
            })
                .catch(() => {
                    this.$Progress.fail();
                    swal.fire(
                        'Not Created!',
                        'Pastikan anda mengisikan data dengan benar',
                        'error'
                    )
                });
        },
        getAllUsers(page) {
            axios.get('/users', {
                params: this.selected
            }).then(response => {
                this.users = response
            });
        },
        deleteUser(id) {
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
                    this.form.delete('/users/' + id).then(() => {
                        Fire.$emit('AfterCreated');
                        swal.fire(
                            'Deleted!',
                            'Data user sudah dihapus',
                            'success'
                        )
                    });
                }
            })
        },
        updateUser() {
            this.$Progress.start();
            this.form.put('/users/' + this.form.id).then(() => {
                Fire.$emit('AfterCreated');
                $("#addNew").modal('hide');
                swal.fire(
                    'Updated!',
                    'Data user sudah diupdate',
                    'success'
                )
                this.$Progress.finish();
            })
                .catch(() => {
                    this.$Progress.fail();
                    swal.fire(
                        'Not Updated!',
                        'Pastikan anda mengisikan data dengan benar',
                        'error'
                    )
                });
        },
        onFileChange(e) {
            this.import_file = e.target.files[0];
        },
        importExcel() {
            let formData = new FormData();
            formData.append('import_file', this.import_file);
            this.$Progress.start();
            axios.post('/import/excel', formData, {
                headers: {'content-type': 'multipart/form-data'}
            })
                .then(response => {
                    if (response.status === 200) {
                        Fire.$emit('AfterCreated');
                        swal.fire(
                            'Imported!',
                            'Data user sudah diimport',
                            'success'
                        )
                    }
                })
                .catch(error => {
                    this.$Progress.fail();
                    this.error = error.response.data
                    swal.fire(
                        'Not Imported!',
                        'Pastikan dokumen yang anda upload berformat xls/xlsx',
                        'error'
                    )
                });
        },
    },
    created() {
        this.getAllUsers();
        this.getAllRoles();
        Fire.$on('AfterCreated', () => {
            this.getAllUsers();
        })
    },
    mounted() {
        console.log('Component mounted.')
    }
}
</script>
