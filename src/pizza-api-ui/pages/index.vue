<template>
  <div v-if="$store.getters.getAccess">
    <div class="card bg-light mb-3 shadow mt-4">
      <div class="card-header h4">
        <div class="float-left">Previous Orders</div>
        <SearchComponent :data-set="'order'" :per-page="5"/>
      </div>
      <div class="card-body pt-0 pb-0">
        <p class="h3 text-center mt-3" v-if="orderDatas.data && !orderDatas.data[0] && orderDatas != 'loading'">There is no order.</p>
        <p class="h3 text-center mt-3 mb-3" v-if="orderDatas == 'loading'">Loading...</p>
        <table class="table" v-if="orderDatas.data && orderDatas.data[0]">
          <tbody>
            <Order
              v-for="order in orderDatas.data"
              :key="order.id"
              :order-datas="order"
              :isAdmin="false"
            />
          </tbody>
        </table>
      </div>

      <div class="card-footer text-muted" v-if="orderDatas.data && orderDatas.data[0]">
        <PaginationComponent :pagination-data="orderDatas.meta" :per-page="5" :data-set="'order'"  />
      </div>
    </div>

    <div class="card bg-light mb-3 shadow mt-4">
      <div class="card-header h4">
        <div class="float-left">Pizza Collection</div>
        <SearchComponent :data-set="'product'" :per-page="12"/>
      </div>
      <div class="card-body pt-0">
        <p class="h3 text-center mt-3" v-if="productDatas.data && !productDatas.data[0] && productDatas != 'loading'">There is no pizza.</p>
        <p class="h3 text-center mt-3" v-if="productDatas == 'loading'">Loading...</p>
        <div class="row" v-if="productDatas.data && productDatas.data[0]">
          <PizzaCart
            v-for="(product, index) in productDatas.data"
            :key="index"
            :product-index="index"
            :product-datas="product"
          />
        </div>
      </div>

      <div class="card-footer text-muted" v-if="productDatas.data && productDatas.data[0]">
        <PaginationComponent :pagination-data="productDatas.meta" :data-set="'product'" :per-page="8"  />
      </div>
    </div>

    <b-modal ref="product-modal" id="modal-lg" size="lg" hide-footer :title="modelDatas.name">
      <div class="card">
        <div class="row">
          <aside class="col-sm-5 border-right">
            <article class="gallery-wrap">
              <div class="img-big-wrap">
                <div>
                  <a href="#">
                    <img
                      :src="'http://api.pizzapp.test/uploads/products/'+modelDatas.image"
                      width="300"
                      height="300"
                      class="img-responsive"
                      v-if="modelDatas.image"
                    />
                  </a>
                </div>
              </div>
              <!-- slider-product.// -->
              <!--<div class="img-small-wrap">
                      <div class="item-gallery">
                        <img src="http://placehold.it/64x64" />
                      </div>
                      <div class="item-gallery">
                        <img src="http://placehold.it/64x64" />
                      </div>
                      <div class="item-gallery">
                        <img src="http://placehold.it/64x64" />
                      </div>
                      <div class="item-gallery">
                        <img src="http://placehold.it/64x64" />
                      </div>
              </div>-->
              <!-- slider-nav.// -->
            </article>
            <!-- gallery-wrap .end// -->
          </aside>
          <aside class="col-sm-7">
            <article class="card-body p-5">
              <h3 class="title mb-3">{{modelDatas.name}}</h3>

              <p class="price-detail-wrap">
                <span class="price h3 text-warning">
                  <span class="currency">US $</span>
                  <span class="num">{{modelDatas.price}}</span>
                </span>
                <span>/per</span>
              </p>
              <!-- price-detail-wrap .// -->
              <dl class="item-property">
                <dt>Description</dt>
                <dd>
                  <p>{{modelDatas.description}}</p>
                </dd>
              </dl>

              <hr />
              <div class="row">
                <div class="col-sm-5">
                  <dl class="param param-inline">
                    <dt>Quantity:</dt>
                    <dd>
                      <input
                        type="number"
                        class="form-control text-center"
                        v-model="modelDatas.quantity"
                        value="1"
                      />
                    </dd>
                  </dl>
                  <!-- item-property .// -->
                </div>
                <!-- col.// -->
                <div class="col-sm-7">
                  <dl>
                    <dt>Size:</dt>
                    <dd>
                      <b-form-group inline="true">
                        <b-form-radio
                          v-for="size in sizeData"
                          :key="size.id"
                          v-model="modelDatas.size_id"
                          name="some-radios"
                          :value="size.id"
                          inline
                          @change="setSizeName(size.short_name)"
                        >{{size.short_name}}</b-form-radio>
                      </b-form-group>
                    </dd>
                  </dl>
                  <!-- item-property .// -->
                </div>
                <!-- col.// -->
              </div>
              <!-- row.// -->
              <hr />
              <!--<a href="javascript:void(0)" class="btn btn-lg btn-secondary text-uppercase">Buy now</a>-->
              <a
                href="javascript:void(0)"
                class="btn btn-lg btn-secondary text-uppercase"
                @click="addToCart()"
              >
                <i class="fa fa-cart-plus"></i> Add to cart
              </a>
            </article>
            <!-- card-body.// -->
          </aside>
          <!-- col.// -->
        </div>
        <!-- row.// -->
      </div>
      <!-- card.// -->
    </b-modal>


  </div>
</template>

<script>
import Order from "@/components/orders/order";
import PaginationComponent from "@/components/PaginationComponent";
import SearchComponent from "@/components/SearchComponent";
import PizzaCart from "@/components/pizzaCollection/Pizza-Cart";

export default {
  layout: "default",
  components: {
    Order,
    PaginationComponent,
    SearchComponent,
    PizzaCart
  },
  data() {
    return {
      pagination: {
        page: 1,
        search: null,
        perPage : 8
      },
      modelDatas: {},
      orderData : {id:1},
      productData : {id:1}
    };
  },
  beforeMount() {
    this.$store.dispatch("getProducts", this.pagination);
    this.pagination.perPage = 5;
    this.$store.dispatch("getOrders", this.pagination);
    if (this.$store.state.Sizes == null) {
      this.$store.dispatch("getDatas",{url:"/product-size?page=all",mutationName:"setSizes"});
    }
  },
  methods: {
    addToCart() {
      if (this.modelDatas.quantity <= 0) {
        this.showAlert(
          "warning",
          "Alert",
          "The number of products to be added cannot be less than one"
        );
        return;
      }

      if (this.modelDatas.size_id == null) {
        this.showAlert(
          "warning",
          "Alert",
          "Please select the pizza size"
        );
        return;
      }

      if (this.modelDatas.quantity > 0 && this.modelDatas.size_id != null) {
        this.$refs['product-modal'].hide();
        this.$store.dispatch("addToCart", this.modelDatas);
        this.showAlert(
          "success",
          "Alert",
          "Product added to cart"
        );
        this.$store.dispatch("setModelData",{});
        this.modelDatas.size_id = 0;
        this.modelDatas.quantity = 1;
        this.modelDatas = {};
      }
    },
    setSizeName(name) {
      this.modelDatas.size_name = name;
    }
  },
  computed: {
    productDatas() {
      return this.$store.getters.getProduct;
    },
    orderDatas() {
      return this.$store.getters.getOrder;
    },
    modelData() {
      return this.$store.getters.getModelData;
    },
    sizeData() {
      return this.$store.getters.getSizes;
    }
  },
  watch: {
    modelData() {
      this.modelDatas = this.modelData;
      this.modelDatas.product_id = this.modelData.id;
      this.modelDatas.quantity = 1;

      console.log(this.modelDatas)
    },
    orderDatas(){
      this.orderData = this.orderDatas.data;
    },
    productDatas(){
      this.productData = this.productDatas.data;
    }
  }
};
</script>

<style>
.gallery-wrap .img-big-wrap img {
  height: 305px;
  width: auto;
  display: inline-block;
  cursor: zoom-in;
  margin: 5px;
}

.gallery-wrap .img-small-wrap .item-gallery {
  width: 64px;
  height: 64px;
  border: 1px solid #ddd;
  margin: 7px 2px;
  display: inline-block;
  overflow: hidden;
}

.gallery-wrap .img-small-wrap {
  text-align: center;
}

.gallery-wrap .img-small-wrap img {
  max-width: 100%;
  max-height: 100%;
  object-fit: cover;
  border-radius: 4px;
  cursor: zoom-in;
}
</style>
