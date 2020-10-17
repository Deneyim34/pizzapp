import { InputGroupPlugin } from "bootstrap-vue";
import Cookie from 'js-cookie';

const state = {
  Cart:[],
  CartTotal: 0
};

const mutations = {
  setCart(state, value)
  {
    state.Cart = value;
  },
  setCartTotal(state, value)
  {
    state.CartTotal = value;
  },
  addToCart(state,value){
    if(state.Cart.length == 0)
    {
      state.Cart.push(value);
    }
    else
    {
      let inCart = false;
      state.Cart.forEach((item) => {
        if(item.product_id == value.product_id && item.size_id == value.size_id)
        {
          inCart = true;
          item.quantity = parseInt(item.quantity) + parseInt(value.quantity);
          return;
        }
      });

      if(!inCart)
      {
        state.Cart.push(value);
      }
    }
    localStorage.setItem("cart",JSON.stringify(state.Cart));

  },
  removeFromCart(state, value)
  {
    state.Cart.splice(value,1);
    if(state.Cart.length == 0)
    {
      localStorage.setItem("cart",JSON.stringify([]));
      //localStorage.removeItem("cart");
      state.Cart = [];
      state.CartTotal = 0;
      //$cookies.delete("cart");
    }
    else
    {
      localStorage.setItem("cart",JSON.stringify(state.Cart));
    }

  }
};

const actions = {
  giveOrder(vuexContext, value){
    vuexContext.dispatch('getCart');

      const options = {
        params:{products:vuexContext.getters.getCart,cardDatas:value}
      };

      const headers = {
        withCredentials: true,
        //'Content-Type': 'application/x-www-form-urlencoded',
        headers: {
          'Accept':'application/json',
          'Content-Type':'application/json',
          'Authorization' : 'Bearer '+vuexContext.getters.getAuthToken
         }

      };

      this.$axios.post("/order",options,headers)
      .then(response => {
        console.log(response.data);
        if(response.status == 201)
        {
          localStorage.setItem("cart",JSON.stringify([]));
          vuexContext.dispatch('getCart');
          this.$router.push("/thanks");
        }
        //vuexContext.commit("setOrder",response.data);
      })
      .catch((err) => { console.log(err);})

  },
  getCart(vuexContext, value){

    if(vuexContext.getters.getCart.length == 0)
    {
      localStorage.setItem("cart",JSON.stringify([]));
    }
    vuexContext.commit('setCart', JSON.parse(localStorage.getItem("cart")));
    vuexContext.dispatch('calculateTotal');



  },
  addToCart(vuexContext, value){
    vuexContext.commit('setCart', JSON.parse(localStorage.getItem("cart")));
    vuexContext.commit("addToCart",value);
    vuexContext.dispatch('getCart');
    console.log(vuexContext.getters.getCart);

  },
  changeQuantity(vuexContext, value){

    localStorage.setItem("cart",JSON.stringify(vuexContext.state.Cart));
    vuexContext.dispatch('getCart');

  },
  removeFromCart(vuexContext, value){

    vuexContext.commit('setCart', JSON.parse(localStorage.getItem("cart")));
    vuexContext.commit("removeFromCart",value);
    vuexContext.dispatch('getCart');
  },
  calculateTotal(vuexContext, value)
  {
    if(vuexContext.state.Cart != null)
    vuexContext.state.CartTotal = vuexContext.state.Cart.reduce((total,item) => {
      return total + (item.price * item.quantity);
    },0)
  }
};

const getters = {
  getCart(state)
  {
    return state.Cart;
  },
  getCartTotal(state)
  {
    return state.CartTotal;
  }
};

export default{
state,
mutations,
actions,
getters
}
