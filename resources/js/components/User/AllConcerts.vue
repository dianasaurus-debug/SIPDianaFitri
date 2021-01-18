<template>
    <div>
        <div class="row align-items-center">
            <div class="col-12">
                <h3 class="blue text-center mb-3 mt-5"><b>CONCERTS</b></h3>
                <div class="text-center mt-5">
                    <pulse-loader :loading="loading && openModal == false"></pulse-loader>
                </div>
            </div>
        </div>
        <div class="row" v-show="loading == false ">
            <div class="col-md-4 mx-auto my-6" v-for="concert in concerts" :key="concert.id">
                <div class="card" style="width: 18rem;">
                    <img :src="'/images/concerts/'+concert.gambar" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{ concert.judul }}</h5>
                        <p class="card-text"><i class="fas fa-map-marker"></i> {{ concert.tempat }}</p>
                        <p class="card-text"><i class="fas fa-calendar"></i> {{ concert.tanggal }}</p>
                        <p class="card-text"><i class="fas fa-clock"></i> {{ concert.duration }}</p>
                        <p class="card-text"><i class="fas fa-user"></i> <b>{{ concert.idol }}</b></p>
                        <a href="#" class="btn btn-primary" @click="viewConcert(concert.id)">Beli Tiket</a>
                    </div>
                </div>
            </div><!-- /.col-lg-4 -->
        </div><!-- /.row -->
        <div class="modal fade" id="viewConcert" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:#bfd1e5">
                        <h5 class="modal-title" v-show="viewData == false">View Concerts</h5>
                        <h5 class="modal-title ml-5" v-show="viewData == true">{{ concert.judul }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="color : red">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <pulse-loader :loading="loading"></pulse-loader>
                        </div>

                        <div class="row" v-show="loading == false && viewData == true && viewTicket == false">
                            <div class="col-4">
                                <img class="img-thumbnail" :src="'/images/concerts/'+concert.gambar">
                            </div>
                            <div class="col-8">
                                <table class="table" style="border: none;font-size : 17px">
                                    <tbody>
                                    <tr>
                                        <td><i class="fas fa-map-marker"></i> {{ concert.tempat }}</td>
                                        <td><i class="fas fa-calendar"></i> {{ concert.tanggal }}</td>
                                    </tr>
                                    <tr>
                                        <td><i class="fas fa-chair"></i> {{ concert.jumlah_seat }}</td>
                                        <td><i class="fas fa-clock"></i> {{ concert.duration }} Menit</td>
                                    </tr>
                                    <tr>
                                        <td><i class="fas fa-user"></i> {{ concert.idol }}</td>
                                        <td><i class="fas fa-money-bill"></i> Rp. {{ concert.price }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <button class="btn btn-success" @click="getBook(concert)">Book</button>
                                        </td>
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
                                        <td class="font-weight-bold">Nama Konser :</td>
                                        <td>{{ ticket.concert }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Serial Number :</td>
                                        <td>{{ ticket.serial_number }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Seat Number :</td>
                                        <td>{{ ticket.seat_number }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Harga :</td>
                                        <td>{{ ticket.harga }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Atas Nama :</td>
                                        <td>{{ ticket.user }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Tanggal :</td>
                                        <td>{{ ticket.waktu_beli | formatDate }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <a class="btn btn-success" href="/user/mytickets">My Tickets
                                            </a>
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
            concerts: {},
            viewData : false,
            viewTicket : false,
            loading : false,
            openModal : false,
            ticket : {},
            concert : {},
            createTicket : {
                jumlah_seat : '',
                concert_id : '',
            }
        }
    },
    methods: {
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
        },
        getConcerts() {
            this.loading = true;
            axios.get('/concerts/all').then(concerts => {
                this.concerts = concerts.data.data;
                this.failed = false;
            }).catch((error) => {
                this.failed = true;
            }).finally(() => {
                this.loading = false;
            });
        }
    },
    created() {
        this.getConcerts();
    },

}
</script>
