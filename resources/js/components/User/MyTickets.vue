<template>
    <div>
        <div class="row align-items-center"s>
            <div class="col-12">
                <h3 class="blue text-center mb-3 mt-5"><b>My Tickets</b></h3>
                <div class="text-center mt-5"><pulse-loader :loading="loading"></pulse-loader></div>
            </div>
        </div>
        <div class="row" v-show="loading == false && tickets.length == 0">
            <div class="col-12">Belum ada tiket</div>
        </div>
        <div class="row" v-show="loading == false && tickets.length > 0">
            <div class="col-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Konser</th>
                        <th scope="col">No. Seri</th>
                        <th scope="col">No. Seat</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="ticket in tickets" :key="ticket.id">
                        <th>{{ticket.concert}}</th>
                        <td>{{ticket.serial_number}}</td>
                        <td>{{ticket.seat_number}}</td>
                        <td>{{ticket.harga}}</td>
                        <td>{{ticket.waktu_beli}}</td>
                        <td>
                            <button @click="deleteTickets(ticket.id)" class="btn btn-danger" style="cursor: pointer">Cancel</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <hr>
                <a class="btn btn-success" style="color : white;cursor: pointer" href="/mytickets/invoice" target="_blank"><i class="fas fa-file-pdf"></i> Generate Invoice</a>
            </div>
        </div><!-- /.row -->
    </div>
</template>
<script>
export default {
    data() {
        return {
            tickets : {},
            loading : false,
        }
    },
    methods : {
        getAllTickets(){
            this.loading = true;
            axios.get('/mytickets').then( tickets => {
                this.tickets = tickets.data;
                console.log(tickets);
                this.failed = false;
            }). catch((error) => {
                this.failed = true;
            }). finally(()=> {
                this.loading = false;
            });
        },
        deleteTickets(id) {
            swal.fire({
                title: 'Anda yakin?',
                text: "Tindakan ini tidak bisa dibalikkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, batalkan'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('/ticket/' + id).then(() => {
                        Fire.$emit('AfterCreated');
                        swal.fire(
                            'Deleted!',
                            'Booking sudah dibatalkan',
                            'success'
                        )
                    });
                }
            })
        },
    },
    created(){
        Fire.$on('AfterCreated', () => {
            this.getAllTickets();
        })
        this.getAllTickets();
    },

}
</script>
