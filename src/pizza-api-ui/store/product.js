const state = {
  product:"loading"
};

const mutations = {
  setProduct(state, value)
  {
    state.product = value;
  }
};

const actions = {
  getProducts(vuexContext, value){
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
      vuexContext.commit("setProductSearch",value.search);
      if(value.searchType != null)
      {
        vuexContext.commit("setProductSearchType",value.searchType);
      }
    }
    vuexContext.commit("setProduct","loading");

    let url = "/product?page="+value.page+(value.perPage != null ? "&total="+value.perPage : "")+(value.search != null ? "&search="+value.search : "");
    this.$axios.get(url,options)
        .then(response => {
          vuexContext.commit("setProduct",response.data);
        })
        .catch((err) => {
          console.log(err);
          vuexContext.dispatch("statusControl",err.response.status);
        })
  },
  getProductData(vuexContext, value){
    const options = {
      withCredentials: true,
      headers: {
        //'Content-Type': 'application/x-www-form-urlencoded',
        'Accept':'application/json',
        'Content-Type':'application/json',
        'Authorization' : 'Bearer '+vuexContext.getters.getAuthToken
      },
    };
    vuexContext.commit("setProduct","loading");
    this.$axios.get("/product/"+value,options)
        .then(response => {
          vuexContext.commit("setProduct",response.data);
        })
        .catch((err) => {
          console.log(err);
          vuexContext.dispatch("statusControl",err.response.status);
        })
  },
  addProduct(vuexContext, value){
      const options = {
        params:value
      };

      const headers = {
        withCredentials: true,
        headers: {
          'Accept':'application/json',
          'Content-Type': 'multipart/form-data',
          'Authorization' : 'Bearer '+vuexContext.getters.getAuthToken
         }

      };

      return this.$axios.post("/admin/product",value,headers)
      .then(response => {
        console.log(response.data);
        if(response.data == "ok")
        {
          vuexContext.commit("setProduct",{});
          return response.data;

        }
        //vuexContext.commit("setOrder",response.data);
      })
      .catch((err) => {
        console.log(err);
        vuexContext.dispatch("statusControl",err.response.status);
      })

  },
  updateProduct(vuexContext, value){

    const headers = {
      withCredentials: true,
      headers: {
        'Accept':'application/json',
        'Content-Type': 'multipart/form-data',
        'Authorization' : 'Bearer '+vuexContext.getters.getAuthToken
       }

    };

    return this.$axios.post("/admin/product/"+value.get("id"),value,headers)
    .then(response => {
      if(response.status == 200)
      {
        return response.data;
      }
    })
    .catch((err) => {
      console.log(err);
      vuexContext.dispatch("statusControl",err.response.status);
    })
  },
  deleteProduct(vuexContext, value){
    const headers = {
      withCredentials: true,
      headers: {
        'Accept':'application/json',
        'Content-Type':'application/json',
        'Authorization' : 'Bearer '+vuexContext.getters.getAuthToken
       }

    };

    return this.$axios.delete("/admin/product/"+value,headers)
    .then(response => {
      if(response.status == 204 || response.status == 417)
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
  getProduct(state)
  {
    return state.product;
  }
};

export default{
state,
mutations,
actions,
getters
}
