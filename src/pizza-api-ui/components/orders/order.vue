<template>
  <tr>
    <th scope="row" style="width: 50px;" class="align-middle">{{orderDatas.order_id}}</th>
    <td class="align-middle" style="width: 600px;">
      <OrderItem v-for="product in orderDatas.products" :key="product.id" :order-datas="product"/>
    </td>
    <td class="align-middle" style="width: 100px;">{{orderDatas.order_time}}</td>
    <td class="align-middle" style="width: 70px;" v-if="!isAdmin">
      <strong>{{orderDatas.status.name}}</strong>
    </td>
    <td class="align-middle" style="width: 200px;" v-if="isAdmin">
        <select class="form-control custom-select roundless-input" id="inputCountry"  @change="updateStatus($event)">
            <option :value="status.id" :selected="status.id == orderDatas.status.id" v-for="status in statuses" :key="status.id">{{ status.name }}</option>
        </select>
    </td>
    <td class="align-middle text-right" style="width: 100px;">${{orderDatas.total_price}}</td>
    <!--<td class="align-middle text-right" style="width: 150px;" v-if="!isAdmin">
      <a href="#" class="btn btn-secondary btn-block">
        <i class="fa fa-cart-plus" aria-hidden="true"></i> Re-Order
      </a>
    </td>-->
    <td class="align-middle text-right" style="width: 150px;" v-if="isAdmin">
      <nuxt-link :to="'/admin/orders/'+orderDatas.id" tag="a"
        class="btn btn-secondary"><i class="fa fa-eye" aria-hidden="true"></i></nuxt-link>
      <a href="javascript:void(0)"
        class="btn btn-danger" @click="removeOrder()"><i class="fa fa-remove" aria-hidden="true"></i></a>
    </td>
  </tr>
</template>

<script>
import OrderItem from '@/components/orders/order-item'
export default {
    components: {
        OrderItem
    },
    data(){
      return {
          pagination : {
          page : 1,
          search : null,
          searchType : null
        }
      }
    },
    props: {
        orderDatas : {
            required : true,
            type: Object
        },
        statuses : {
          required : false
        },
        isAdmin : {
          required : true,
          type : Boolean
        }
    },
    methods:{
      updateStatus(e){
        this.$store.dispatch("updateOrder",{status_id:e.target.value, id:this.orderDatas.id});
      },
      removeOrder(){
        this.$store.dispatch("deleteOrder",this.orderDatas.id)
        .then(response =>{
          this.$store.dispatch("getOrders",this.pagination);
          this.showAlert(
            "success",
            "Alert",
            "Order removed successfully"
          );
        })
        .catch((err) => { console.log(err);});
        }
    }
};
</script>

<style>
</style>
