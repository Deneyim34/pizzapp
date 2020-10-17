<template>
  <div v-if="customer">
    <label for="inputFirstName" class="sr-only">First Name</label>
    <input
      type="text"
      id="inputFirstName"
      class="form-control top-input"
      placeholder="First Name"
      required
      autofocus
      v-model="customer.name"
      :class="{'is-invalid' : $v.customer.name.$error || errors.name}" @blur="$v.customer.name.$touch()"
    />
    <label for="inputLastName" class="sr-only">Last Name</label>
    <input
      type="text"
      id="inputLastName"
      class="form-control roundless-input"
      placeholder="Last Name"
      required
      v-model="customer.surname"
      :class="{'is-invalid' : $v.customer.surname.$error || errors.surname}" @blur="$v.customer.surname.$touch()"
    />
    <div v-if="allFields">
      <label for="inputAddress" class="sr-only">Address</label>
      <input
        type="text"
        id="inputAddress"
        class="form-control roundless-input"
        placeholder="Address"
        required
        v-model="customer.address"
        :class="{'is-invalid' : $v.customer.address.$error || errors.address}" @blur="$v.customer.address.$touch()"
      />
      <label for="inputMobile" class="sr-only">Mobile</label>
      <input
        type="tel"
        id="inputMobile"
        class="form-control roundless-input"
        placeholder="Mobile"
        required
        v-model="customer.phone"
        :class="{'is-invalid' : $v.customer.phone.$error || errors.phone}" @blur="$v.customer.phone.$touch()"
      />
      <div class="selectArea">
        <label for="inputCountry" class="sr-only">Country</label>
        <select class="form-control custom-select roundless-input" id="inputCountry" required v-model="customer.country_id" @change="getCities($event.target.value)" :class="{'is-invalid' : $v.customer.country_id.$error || errors.country_id}" @blur="$v.customer.country_id.$touch()">
          <option selected>Country</option>
          <option v-for="country in countries" :key="country.id" :value="country.id" :selected="country.id == customer.country_id">{{country.name}}</option>
        </select>
      </div>
      <div class="selectArea">
        <label for="inputCity" class="sr-only">City</label>
        <select class="form-control custom-select roundless-input" id="inputCity" required v-model="customer.city_id" @change="getDistricts({city:$event.target.value,country:customer.country_id})" :class="{'is-invalid' : $v.customer.city_id.$error || errors.city_id}" @blur="$v.customer.city_id.$touch()">
          <option value="0">City</option>
          <option v-for="city in cities" :key="city.id" :value="city.id">{{city.name}}</option>
        </select>
      </div>
      <div class="selectArea" v-if="customer.city_id > 0">
        <label for="inputDistrict" class="sr-only">District</label>
        <select class="form-control custom-select roundless-input" id="inputDistrict" required v-model="customer.district_id" :class="{'is-invalid' : $v.customer.district_id.$error || errors.district_id}" @blur="$v.customer.district_id.$touch()">
          <option value="0">District</option>
          <option v-for="district in districts" :key="district.id" :value="district.id">{{district.name}}</option>
        </select>
      </div>
    </div>

    <label for="inputEmail" class="sr-only">Email address</label>
    <input
      type="email"
      id="inputEmail"
      class="form-control roundless-input"
      placeholder="Email address"
      required
      v-model="customer.email"
      :class="{'is-invalid' : $v.customer.email.$error || errors.email}" @blur="$v.customer.email.$touch()"
    />
    <label for="inputPassword" class="sr-only">Password</label>
    <input
      type="password"
      id="inputPassword"
      class="form-control roundless-input"
      placeholder="Password"
      v-model="password"
      :class="{'is-invalid' : $v.password.$error || errors.password}" @blur="$v.password.$touch()"
    />
    <label for="inputPasswordAgain" class="sr-only">Password Again</label>
    <input
      type="password"
      id="inputPasswordAgain"
      class="form-control bottom-input"
      placeholder="Password Again"
      v-model="passwordAgain"
      :class="{'is-invalid' : $v.passwordAgain.$error}" @blur="$v.passwordAgain.$touch()"
    />
    <input type="hidden" name="id" v-model="customer.id">
    <button class="btn btn-lg btn-primary btn-block" type="submit"  :disabled="$v.$invalid" @click.prevent="saveCustomer">{{ saveTxt }}</button>
  </div>
</template>

<script>

import {required, minLength, maxLength, requiredIf, sameAs, numeric, email} from 'vuelidate/lib/validators'

export default {

  data(){
    return{
      customer : {
        id:0,
        name:"",
        surname:"",
        email:"",
        phone:"",
        address:"",
        country_id:1,
        city_id:0,
        district_id:0
      },
      errors : {
        name:false,
        surname:false,
        email:false,
        phone:false,
        address:false,
        country_id:false,
        city_id:false,
        district_id:false,
        password:false
      },
      countries : {},
      cities : {},
      districts : {},
      password : "",
      passwordAgain:""

    }
  },
  validations:{
    customer:{
      name:{
        required
      },
      surname:{
        required : requiredIf(function() {return this.allFields;})
      },
      email:{
        required,
        email
      },
      address:{
        required : requiredIf(function() {return this.allFields;})
      },
      phone:{
        required : requiredIf(function() {return this.allFields;})
      },
      country_id:{
        required : requiredIf(function() {return this.allFields;})
      },
      city_id:{
        required : requiredIf(function() {return this.allFields;})
      },
      district_id:{
        required : requiredIf(function() {return this.allFields;})
      }
    },
    password:{
      required : requiredIf(function() {return this.id == 0;}),
      minLength: minLength(8),
      maxLength: maxLength(20),
    },
    passwordAgain:{
      required : requiredIf(function() {return this.id == 0;}),
      minLength: minLength(8),
      maxLength: maxLength(20),
      sameAs : sameAs('password')
    }
  },
  props: {
    allFields: {
      required: true,
      type: Boolean
    },
    saveTxt:{
        required : true,
        type: String
    },
    id:{
    }
  },
  beforeMount(){
    if(this.$store.Countries == null)
    {
      this.$store.dispatch("getDatas",{url:"/country?page=all",mutationName:"setCountries"});
    }

    if(this.id > 0 || this.id == "user")
    {
      this.$store.dispatch("getCustomerData",this.id);
    }
    else{
      this.getCities(this.customer.country_id,true);
      this.customer.country_id = 1;
      this.customer.city_id = 0;
    }

  },
  methods:{
    saveCustomer()
    {
      Object.keys(this.errors).forEach((key) => {
              this.errors[key] = false;
            });

      if(this.id > 0)
      {
        if(this.password != "")
        {
          this.customer.password = this.password;
        }

        this.$store.dispatch("updateCustomer",this.customer)
        .then(response =>{
          if(response.status == 200)
          {
            delete this.customer.password;
            this.password = "";
            this.passwordAgain = "";
            this.showAlert(
              "success",
              "Alert",
              "Customer updated successfully"
            );
          }
          else{
            Object.keys(response).forEach((key) => {
              this.errors[key] = true;
            });
          }

        })
        .catch((err) => { console.log(err);});
      }
      else
      {
        this.customer.password = this.password;

        this.$store.dispatch("addCustomer",this.customer)
        .then(response =>{
          if(response.status == 201)
          {
            this.customer = {
              id: 0,
              country_id : 1,
              city_id : 0
            };
            this.password = "";
            this.passwordAgain = "";
            this.showAlert(
              "success",
              "Alert",
              "New customer added successfully"
            );
          }
          else
          {
           Object.keys(response).forEach((key) => {
              this.errors[key] = true;
            });
          }
        })
        .catch((err) => { console.log(err);});
      }
    },
    getDistricts(DistrictValues){

      this.$store.dispatch("getDatas",{url:"/district",mutationName:"setDistricts", data:{...DistrictValues}});
    },
    getCities(CountryID, first = false){
      if(!first)
      {
        this.customer.city_id = 0;
        this.customer.district_id = 0;
        this.$store.commit("setDistricts",{});
      }

      this.$store.dispatch("getDatas",{url:"/city",mutationName:"setCities", data:{"country":CountryID}});
    },

  },
  computed:{
    customerDatas()
    {
      return this.$store.getters.getCustomer.data;
    },
    Countries()
    {
      return this.$store.getters.getCountries;
    },
    Cities()
    {
      return this.$store.getters.getCities;
    },
    Districts()
    {
      return this.$store.getters.getDistricts;
    }
  },
  watch:{
    customerDatas(value){
      this.customer = this.customerDatas;

      if(this.customerDatas)
      {
        this.getCities(this.customer.country_id,true);

        let districtValues = {
          country : this.customer.country_id,
          city : this.customer.city_id,
        }
        this.getDistricts(districtValues);
      }
    },
    Countries(value){
      this.countries = this.Countries;
    },
    Cities(value){
      this.cities = this.Cities;
    },
    Districts(value){
      this.districts = this.Districts;
    }
  }
};
</script>

<style>
</style>
