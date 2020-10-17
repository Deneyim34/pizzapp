const state = {
  order:"loading"
};

const mutations = {
  setOrder(state, value)
  {
    state.order = value;
  }
};

const actions = {
  getOrders(vuexContext, value){

    const options = {
      withCredentials: true,
      headers: {
        //'Content-Type': 'application/x-www-form-urlencoded',
        'Accept':'application/json',
        'Content-Type':'application/json',
        'Authorization' : 'Bearer '+vuexContext.getters.getAuthToken
      },
    };

    if(value.search != null)
    {
      vuexContext.commit("setOrderSearch",value.search);
      if(value.searchType != null)
      vuexContext.commit("setOrderSearchType",value.searchType);
    }
    vuexContext.commit("setOrder","loading");

    let url = "/order?page="+value.page+(value.perPage != null ? "&total="+value.perPage : "")+(value.search != null ? "&search="+value.search+"&type="+value.searchType : "");
    this.$axios.get(url,options)
        .then(response => {

          vuexContext.commit("setOrder",response.data);
        })
        .catch((err) => {
          console.log(err);
          vuexContext.dispatch("statusControl",err.response.status);
        })
  },
  getOrderData(vuexContext, value){
    const options = {
      withCredentials: true,
      headers: {
        //'Content-Type': 'application/x-www-form-urlencoded',
        'Accept':'application/json',
        'Content-Type':'application/json',
        'Authorization' : 'Bearer '+vuexContext.getters.getAuthToken
      },
    };
    vuexContext.commit("setOrder","loading");

    this.$axios.get("admin/order/"+value,options)
        .then(response => {
          vuexContext.commit("setOrder",response.data);
        })
        .catch((err) => {
          console.log(err);
          vuexContext.dispatch("statusControl",err.response.status);
        })
  },
  updateOrder(vuexContext, value){
    const options = {
      ...value
    };

    const headers = {
      withCredentials: true,
      headers: {
        'Accept':'application/json',
        'Content-Type':'application/json',
        'Authorization' : 'Bearer '+vuexContext.getters.getAuthToken
       }

    };

    this.$axios.put("/admin/order/"+value.id,options,headers)
    .then(response => {
      console.log(response.data);
      //vuexContext.commit("setOrder",response.data);
    })
    .catch((err) => {
      console.log(err);
      vuexContext.dispatch("statusControl",err.response.status);
    })
  },
  deleteOrder(vuexContext, value){
    const headers = {
      withCredentials: true,
      headers: {
        'Accept':'application/json',
        'Content-Type':'application/json',
        'Authorization' : 'Bearer '+vuexContext.getters.getAuthToken
       }

    };

    return this.$axios.delete("/admin/order/"+value,headers)
    .then(response => {
      if(response.status == 204)
        {
          return response.data;
        }
    })
    .catch((err) => {
      console.log(err);
      vuexContext.dispatch("statusControl",err.response.status);
    })
  }
};

const getters = {
  getOrder(state)
  {
    return state.order;
  }
};

export default{
state,
mutations,
actions,
getters
}
