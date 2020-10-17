<template>
  <div class="card bg-light mb-3 shadow mt-4" v-if="$store.getters.getAdminAccess">
    <div class="card-header h4">
      <div class="float-left" v-if="$route.params.id > 0">Edit Customer</div>
      <div class="float-left" v-if="$route.params.id == 'new'">Add New Customer</div>

    </div>
    <div class="card-body pt-1">
      <form action id="form">
        <CustomerForm :all-fields="true" :save-txt="'Save'" :id="$route.params.id"/>
      </form>
    </div>
  </div>
</template>

<script>
import CustomerForm from "@/components/customer/CustomerForm";
import PaginationComponent from "@/components/PaginationComponent";
import SearchComponent from "@/components/SearchComponent";

export default {
  layout:'AdminLayout',
  components: {
    CustomerForm
  }
};
</script>

<style>
 #form input,#form .selectArea, #form button{ margin-top : 10px;}
</style>
