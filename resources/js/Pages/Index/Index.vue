<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-sm-12">
        <h4>Hello Dashboard</h4>
        <ul id="users">
          <li v-for="user in users" :key="user.id" :id="user.id">
            {{ user.name }}
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import { Link, router } from '@inertiajs/vue3'
// import Echo from 'laravel-echo';
import axios from 'axios';

export default {
  components: {
    Link,
  },
  props: {
    message: Object,
  },
  data() {
    return {
      layout: undefined,
      users: [],
    }
  },
  mounted() {
    this.getUsers();
    this.listenForUsersEvent();
  },  
  methods: {
    getUsers() {
      axios.get('/api/users')
        .then((response) => {
          this.users = response.data;
        })
        .catch((error) => {
          console.error('Error fetching users:', error);
        });
    },
    listenForUsersEvent() {
      window.Echo.channel('users')
        .listen('UserCreated', (e) => {
          this.users.push(e.user);
        })
        .listen('UserUpdated', (e) => {
          const user = this.users.find((u) => u.id === e.user.id);
          if (user) {
            user.name = e.user.name;
          }
        })
        .listen('UserDeleted', (e) => {
          this.users = this.users.filter((user) => user.id !== e.user.id);
        });
    }
  },
}
</script>

<style>

</style>
