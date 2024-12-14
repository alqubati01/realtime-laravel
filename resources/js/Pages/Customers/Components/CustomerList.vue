<template>
  <div>
    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>id</th>
            <th>User</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(customer, index) in customers"
            :key="index"
          >
          <td>{{ index + 1 }}</td>
          <td>{{ customer.name }}</td>
          <td>{{ customer.email }}</td>
          <td>{{ customer.phone_number }}</td>
          <td class="text-center"><i class="mdi mdi-dots-vertical"></i></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  components: {
  },
  props: {
    customers: Object,
  },
  data() {
    return {
      layout: undefined,
    }
  },
  mounted() {
    
  },  
  methods: {

  },
}
</script>