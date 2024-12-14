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
            <CustomerList :customers="paginatedData" />
          </div>
          <div class="card-footer">
            <nav aria-label="Page navigation example">
              <ul class="pagination">
                <li class="page-item"><a class="page-link" type="button" @click="prevPage" :disabled="currentPage === 1">Previous</a></li>
                <!-- <span>Page {{ currentPage }} of {{ totalPages }}</span> -->
                <li class="page-item"><a class="page-link" type="button" @click="nextPage" :disabled="currentPage === totalPages">Next</a></li>
              </ul>
            </nav>
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
    failedData: Boolean,
    itemsPerPage: {
      type: Number,
      default: 5,
    },
  },
  data() {
    return {
      layout: undefined,
      loading: true,
      failed: false,
      customers: [],
      totalCustomers: 0,
      currentPage: 1,
      timeout: null,
    }
  },
  mounted() {
    this.timeout = setTimeout(() => {
      if (this.loading) {
        this.failed = true;
      }
    }, 10000);

    if (this.$page.props.failedData === true) {
      this.failed = true
    } else {
      this.listenForCustomersReceived()
    }
  },
  beforeUnmount() {
    window.Echo.leaveChannel("customers");
    clearTimeout(this.timeout);
  },
  computed: {
    totalPages() {
      return Math.ceil(this.totalCustomers / this.itemsPerPage);
    },
    paginatedData() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.customers.slice(start, end);
    },
  },
  methods: {
    listenForCustomersReceived() {
      window.Echo.channel('customers')
        .listen('CustomerReceived', (event) => {
          this.loading = false;
          this.customers = event.customers;
          this.totalCustomers = event.customers.length;
          clearTimeout(this.timeout);
        });
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
  },
}
</script>
