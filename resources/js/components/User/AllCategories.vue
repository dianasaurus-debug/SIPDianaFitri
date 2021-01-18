<template>
    <div>
        <div class="row">
            <div class="col-12">
                <h3 class="blue text-center mb-3 mt-5"><b>CATEGORIES</b></h3>
                <div class="text-center mt-5"><pulse-loader :loading="loading && openModal == false"></pulse-loader></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mx-auto my-6 accordion" v-for="category in categories" :key="category.id" id="myAccordion">
                <div class="card" style="width: 18rem;">
                    <img :src="'/images/idols/'+category.foto" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold">{{category.nama_idol}}</h5><br>
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" v-bind:data-target="'#expandable'+category.id" aria-expanded="false" aria-controls="expandable1">
                            Who is {{category.nama_idol}}?
                        </button>
                        <div v-bind:id="'expandable'+category.id" class="collapse" aria-labelledby="item1Header" data-parent="#myAccordion">
                            <p class="text-align-justify">
                                {{category.desc}}
                            </p>
                        </div>
                        <hr>
                        <button @click="openConcerts(category.id)" class="btn btn-success">Lihat Konser</button>
                    </div>
                </div>
            </div><!-- /.col-lg-4 -->
        </div><!-- /.row -->
        <div class="modal fade" id="viewConcert" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:#bfd1e5">
                        <h5 class="modal-title" v-show="viewData == false">View Concerts</h5>
                        <button class="btn btn-primary" v-show="viewData == true" @click="openConcerts(concert.idol_id)">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <h5 class="modal-title ml-5" v-show="viewData == true">{{concert.judul}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="color : red">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center"><pulse-loader :loading="loading"></pulse-loader></div>
                        <div class="row" v-show="loading == false && concerts.length > 0 && viewData == false && viewTicket == false">
                            <div class="col-12">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">Judul</th>
                                        <th scope="col">Tempat</th>
                                        <th scope="col">Tanggal</th>
                                        <th scope="col">Seat</th>
                                        <th scope="col">Durasi</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="concert in concerts" :key="concert.id">
                                        <th>{{concert.judul}}</th>
                                        <td>{{concert.tempat}}</td>
                                        <td>{{concert.tanggal}}</td>
                                        <td>{{concert.jumlah_seat}}</td>
                                        <td>{{concert.duration}}</td>
                                        <td>
                                            <button class="btn btn-primary" @click="viewConcert(concert.id)">View</button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row" v-show="loading == false && viewData == true && viewTicket == false">
                            <div class="col-4">
                                <img class="img-thumbnail" :src="'/images/concerts/'+concert.gambar">
                            </div>
                            <div class="col-8">
                                <table class="table" style="border: none;font-size : 17px">
                                    <tbody>
                                    <tr>
                                        <td><i class="fas fa-map-marker"></i> {{concert.tempat}}</td>
                                        <td><i class="fas fa-calendar"></i> {{concert.tanggal}}</td>
                                    </tr>
                                    <tr>
                                        <td><i class="fas fa-chair"></i> {{concert.jumlah_seat}}</td>
                                        <td><i class="fas fa-clock"></i> {{concert.duration}} Menit</td>
                                    </tr>
                                    <tr>
                                        <td><i class="fas fa-user"></i> {{concert.idol}}</td>
                                        <td><i class="fas fa-money-bill"></i> Rp. {{ concert.price }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><button class="btn btn-success" @click="getBook(concert)">Book</button></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row" v-show="loading == false && viewTicket == true">
                            <div class="col-12">
                                <table class="table" style="border: none;font-size : 17px">
                                    <tbody>
                                    <tr>
                                        <td class="font-weight-bold">Nama Konser : </td>
                                        <td>{{ticket.concert}}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Serial Number : </td>
                                        <td>{{ticket.serial_number}}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Seat Number : </td>
                                        <td>{{ticket.seat_number}}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Harga : </td>
                                        <td>{{ticket.harga}}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Atas Nama : </td>
                                        <td>{{ticket.user}}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Tanggal : </td>
                                        <td>{{ticket.waktu_beli | formatDate}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <a class="btn btn-success" href="/user/mytickets">My Tickets</a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row" v-show="loading == false && concerts.length == 0">
                            <div class="col-12"><h5 class="text-center">Belum ada konser tersedia</h5></div>
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
            categories : {},
            concerts : {},
            concert : {},
            viewData : false,
            viewTicket : false,
            loading : false,
            openModal : false,
            ticket : {},
            createTicket : {
                jumlah_seat : '',
                concert_id : '',
            }
        }
    },
    methods : {
        openConcerts(id) {
            this.openModal = true;
            this.viewData = false;
            this.viewTicket = false;
            this.getConcerts(id);
            $("#viewConcert").modal('show');
        },
        viewConcert(id) {
            this.openModal = true;
            this.viewData = true;
            this.viewTicket = false;
            this.getConcert(id);
            $("#viewConcert").modal('show');
        },
        getBook(concert){
            this.openModal = true;
            this.viewData = false;
            this.viewTicket = true;
            this.createTicket.concert_id = concert.id;
            this.createTicket.jumlah_seat = concert.jumlah_seat;
            this.createBook();
            $("#viewConcert").modal('show');
        },
        createBook(){

            let formData = new FormData();

            formData.append("concert_id", this.createTicket.concert_id);
            formData.append("jumlah_seat", this.createTicket.jumlah_seat);

            axios.post('/ticket', formData).then( (ticket) => {
                console.log(ticket.data);
                this.ticket = ticket.data;
                this.failed = false;
            }). catch((error) => {
                this.failed = true;
            }). finally(()=> {
                this.loading = false;
            });

            $("#viewConcert").modal('show');
        },
        getCategories(){
            this.loading = true;
            axios.get('/idols/all').then( categories => {
                this.categories = categories.data.data;
                this.failed = false;
            }). catch((error) => {
                this.failed = true;
            }). finally(()=> {
                this.loading = false;
            });
        },
        getConcerts(id){
            this.loading = true;
            axios.get('/concerts/all/'+id).then( concerts => {
                this.concerts = concerts.data.data;
                this.failed = false;
            }). catch((error) => {
                this.failed = true;
            }). finally(()=> {
                this.loading = false;
            });
        },
        getConcert(id){
            this.loading = true;
            axios.get('/concerts/view/'+id).then( concert => {
                this.concert = concert.data;
                this.failed = false;
            }). catch((error) => {
                this.failed = true;
            }). finally(()=> {
                this.loading = false;
            });
        }
    },
    created(){
        this.getCategories();
    },

}
</script>
