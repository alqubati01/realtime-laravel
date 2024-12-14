<template>
  <div class="content-wrapper">
    <div class="row">
      <div v-if="loading">
        <div class="d-flex justify-content-center">
          <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>
      </div>
      <div v-else-if="failed" class="col-lg-12">
        <div class="alert alert-danger">
          <p>Failed to load customer data. Please try reloading the page.</p>
        </div>
      </div>
      <div v-else class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title pt-3">{{ message }}</h4>
          </div>
          <div class="card-body">
            <CustomerList :customers="customers" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Link, router } from '@inertiajs/vue3'
import CustomerList from './Components/CustomerList.vue';


export default {
  components: {
    Link,
    CustomerList
  },
  props: {
    message: Object,
    customersData: Array,
    failedData: Boolean,
  },
  data() {
    return {
      loading: true,
      customers: [],
      layout: undefined,
      serverTime: '',
      failed: false,
      timeout: null,
    }
  },
  mounted() {
    // this.timeout = setTimeout(() => {
    //   if (this.loading) {
    //     this.failed = true;
    //   }
    // }, 10000);

    this.listenForCustomersReceived()
    // if (this.$page.props.failedData === true) {
    //   this.failed = true
    // } else {
    //   // this.startSSE();

    //   window.Echo.channel('customers')
    //     .listen('.customers.received', (e) => {
    //         console.log("Customers received: ", e)
    //     })
    // }
    // if (this.$page.props.customersData.length > 0) {
    //   this.loading = false
    //   this.customers = this.$page.props.customersData
    // } else {
    //   this.startSSE();
    // }
  },
  beforeUnmount() {
    window.Echo.leaveChannel("customers");
    // clearTimeout(this.timeout);
  },
  methods: {
    listenForCustomersReceived() {
      window.Echo.channel('customers')
        .listen('CustomerReceived', (event) => {
          this.loading = false;
          this.customers = event.customers;
          // clearTimeout(this.timeout);
        });
    },
    // startSSE() {
    //   const eventSource = new EventSource('/get-store-customers');

    //   const timeout = setTimeout(() => {
    //       this.loading = false;
    //       this.failed = true;
    //       eventSource.close();
    //     }, 10000);

    //   eventSource.onmessage = (event) => {
    //     clearTimeout(timeout);
    //     const data = JSON.parse(event.data);
    //     this.serverTime = data.time;
    //     this.customers = data.customers;
    //     this.loading = false;
    //     eventSource.close();
    //   };

    //   eventSource.onerror = () => {
    //     clearTimeout(timeout);
    //     this.loading = false;
    //     this.failed = true;
    //     console.error("SSE connection failed.");
    //     eventSource.close();
    //   };
    // },
  },
}
</script>
