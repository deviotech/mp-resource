<template>
    <v-container>
        

        <nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Users</a>
    <a class="nav-item nav-link" id="nav-declined-tab" @click="declinedUsers()" data-toggle="tab" href="#nav-declined" role="tab" aria-controls="nav-declined" aria-selected="false">Declined Users</a>
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
      <v-data-table show-select
                      :headers="table.headers"
                      :items="table.data"
                      :items-per-page="5"
                      class="elevation-1"
        >
            <template v-slot:item.btmnr="{ item }">
                <v-btn small dense @click="showFiles(item)">Show Files</v-btn>
                <v-list-item-group color="primary" v-if="item.show_files">
                    <template v-for="file in item.files">
                        <v-list-item dense :href="file.path" >
                            <v-list-item-content>
                                <v-list-item-title v-text="file.name"></v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </template>
                </v-list-item-group>
            </template>
            <template v-slot:item.email_verified="{ item }">

                <p v-if="item.email_verified_at === null">Unverified</p>
                <p v-else>Verified</p>

            </template>
            <template v-slot:item.activated="{ item }">
                <v-btn small dense v-if="item.active" @click="deactivateAcc(item)">Deactivate</v-btn>
                <v-btn small dense v-else @click="activateAcc(item)">Activate</v-btn>
            </template>
        </v-data-table>
  </div>
  <!-- declined users -->

    <div class="tab-pane fade" id="nav-declined" role="tabpanel" aria-labelledby="nav-declined-tab">
            <div class="text-right pt-2 pb-2">
                <v-btn small dense v-if="deleteAllBtn" @click="deleteAllDeclinedUsers()" onclick="return confirm('Are you sure you want to delete all declined users?')">Delete All Declined Users Data</v-btn>
            </div>
        <table class="table table-md p-3" style="border:1px solid #ccc;">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Username</th>
                <th scope="col">Phone No.</th>
                <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, key, index) in declinedData.data" :key="item.id">
                <th scope="row">{{key +1 }}</th>
                <td>{{item.first_name}} {{item.last_name}}</td>
                <td>{{item.email}}</td>
                <td>{{item.phone}}</td>
                <td><v-btn onclick="return confirm('Are you sure?')" @click="deleteUser(item)" small dense>Delete</v-btn></td>
                </tr>
            </tbody>
            </table>


  </div>

  <!-- end declined users -->
</div>


        
    </v-container>
</template>

<script>
export default {
    name: "HomeUser",

    data() {
        return {
            tab: null,
            declinedData: [],
            deleteAllBtn:false,
            table: {
                headers: [
                    {
                        text: 'ID',
                        value: 'id',
                    },
                    {
                        text: 'Username',
                        value: 'username',
                    },
                    {
                        text: 'Name',
                        value: 'name',
                        sortable: false,
                    },
                    {
                        text: 'Email',
                        value: 'email',
                    },
                    {
                        text: 'Role',
                        value: 'role',
                        sortable: false,
                    },
                    {
                        text: 'BTMNR',
                        value: 'btmnr',
                        sortable: false,
                    },
                    {
                        text: 'registriert seit',
                        value: 'created_at',
                        sortable: false,
                    },
                    {
                        text: 'bestätigt',
                        value: 'email_verified',
                        sortable: false,
                    },
                    {
                        text: 'Activate',
                        value: 'activated',
                        sortable: false,
                    },
                ],
                data: [],
            },
        }
    },

    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
        },
    },

    watch: {
        dialog(val) {
            val || this.close()
        },
    },

    created() {
        this.initialize();
    },

    methods: {
        initialize() {
            axios.get('/back/users').then(response => {
                this.table.data = response.data.data.map(item => {
                    let date = new Date(item.created_at);
                    let year = date.getFullYear();
                    let month = (1 + date.getMonth()).toString().padStart(2, '0');
                    let day = date.getDate().toString().padStart(2, '0');

                    item.created_at = year + '/' + month + '/' + day;

                    item.show_files = false;
                    return item;
                })
            })
        },

        declinedUsers() {
            axios.get('/back/declinedUsers/').then(response => {
                console.log(response.data.data.length);
                if(response.data.data.length > 0){
                    this.deleteAllBtn=true;
                }
                else{
                    this.deleteAllBtn=false;
                }
                this.declinedData = response.data;  
            })
        },

        showFiles(item) {
            item.show_files = !item.show_files;
        },

        activateAcc(item) {
            axios.post('/back/users/' + item.id + '/activate').then(response => {
                item.active = true;
            });
        },

        deactivateAcc(item) {
            axios.post('/back/users/' + item.id + '/deactivate').then(response => {
                item.active = false;
            });
        },
        deleteUser(item) {
            axios.post('/back/users/' + item.id + '/delete').then(response => {
                this.declinedUsers();
            });
        },

        deleteAllDeclinedUsers() {
            axios.get('/back/deleteUsers/').then(response => {
                this.declinedUsers();
                this.deleteAllBtn=false;
            })
        },
    },
}
</script>
