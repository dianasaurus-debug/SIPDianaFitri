<template>
    <div>
        <div class="col-12 mt-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Users</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-success"@click="newModal">
                            Tambah  <i class="fas fa-user-plus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th>Nama</th>
                            <th>E-Mail</th>
                            <th>No. HP</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="user in users.data" :key="user.id" v-show="user.email!=authuser.email">
                            <td>{{ user.name}}</td>
                            <td>{{ user.email }}</td>
                            <td>{{ user.nomor_hp}}</td>
                            <td>{{ user.username }}</td>
                            <td>{{ user.role }}</td>
                            <td>
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
                <div class="card-footer">
                    <pagination :data="users" @pagination-change-page="getAllUsers"></pagination>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode">Tambah User</h5>
                        <h5 class="modal-title" v-show="editmode" >Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="color : red">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateUser() : createUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" placeholder="Nama">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.email" type="email" name="email"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" placeholder="E-Mail">
                                <has-error :form="form" field="email"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.username" type="text" name="username"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('username') }" placeholder="Username">
                                <has-error :form="form" field="username"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.nomor_hp" type="text" name="nomor_hp"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('nomor_hp') }" placeholder="Nomor Telepon">
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
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" placeholder="Password">
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
    </div>
</template>

<script>
export default {
    data() {
        return {
            users : {},
            editmode : false,
            roles : [],
            authuser : auth,
            form: new Form({
                id : '',
                name: '',
                email: '',
                username: '',
                nomor_hp:'',
                role_id : '',
                password: '',
            })
        }
    },
    methods: {
        newModal(){
            this.form.reset();
            this.editmode = false;
            $("#addNew").modal('show');
        },
        editModal(user){
            this.form.reset();
            this.editmode = true;
            this.form.fill(user);
            $("#addNew").modal('show');
        },
        getAllRoles(){
            axios.get('/roles').then(roles => {
                this.roles = roles.data;
            })
        },
        createUser() {
            this.$Progress.start();
            this.form.post('/users').then( () => {
                Fire.$emit('AfterCreated');
                $("#addNew").modal('hide');
                toast.fire({
                    icon: 'success',
                    title: 'User has been successfully added!'
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
        getAllUsers(page) {
            let uri = '/users?page=' + page;
            axios.get(uri).then(response => {
                return response.data;
            }).then(data => {
                this.users = data;
            });
        },
        deleteUser(id){
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
                    this.form.delete('/users/'+id).then(()=> {
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
        updateUser(){
            this.$Progress.start();
            this.form.put('/users/'+this.form.id).then( () => {
                Fire.$emit('AfterCreated');
                $("#addNew").modal('hide');
                swal.fire(
                    'Updated!',
                    'Data user sudah diupdate',
                    'success'
                )
                this.$Progress.finish();
            })
                .catch( () => {
                    this.$Progress.fail();
                    swal.fire(
                        'Not Updated!',
                        'Pastikan anda mengisikan data dengan benar',
                        'error'
                    )
                });
        },
    },
    created(){
        this.getAllUsers();
        this.getAllRoles();
        Fire.$on('AfterCreated', ()=> {
            this.getAllUsers();
        })
    },
    mounted() {
        console.log('Component mounted.')
    }
}
</script>
