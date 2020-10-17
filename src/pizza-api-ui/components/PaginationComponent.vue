<template>
  <nav aria-label="Page navigation example">
    <ul class="pagination justify-content-end">
      <li :class="[{disabled: !pagination.prev_page}]" class="page-item"><a class="page-link" href="javascript:void(0);" @click="getDatas({page: pagination.prev_page, perPage: perPage, search: search, searchType: searchType})">&laquo;</a></li>
      <li v-for="index in pagination.pages" :key="index" :class="[{active : index == pagination.current_page},{disabled: index === '...'}]" class="page-item">
          <a class="page-link" href="javascript:void(0);" v-if="index !== '...'" @click="getDatas({page: index, perPage: perPage, search: search, searchType: searchType})">{{index}}</a>
          <a class="page-link" href="javascript:void(0);" v-if="index === '...'">{{index}}</a>
      </li>
      <li :class="[{disabled: !pagination.next_page}]" class="page-item"><a class="page-link" href="javascript:void(0);" @click="getDatas({page: pagination.next_page, perPage: perPage, search: search, searchType: searchType})">&raquo;</a></li>
      <!--<li class="page-item"><p class="page-link text-dark" href="javascript:void(0);">Sayfa {{pagination.current_page+"/"+pagination.last_page}}</p></li>-->
    </ul>
  </nav>
</template>

<script>

export default {
  data(){
    return {
      pagination : {},
      search: null,
      searchType :  null,
      pageData : this.paginationData,
      actionName : ""
    }
  },
  props:{
    paginationData : {
      request: true
    },
    dataSet: {
      required : true,
      type: String
    },
    perPage:{
      type: Number
    }
  },
  created(){
    this.dataPrepare(this.dataSet);
    this.makePagination(this.paginationData);
  },
  watch:{
    paginationData(value){

        this.makePagination(this.paginationData);

    }
  },
  methods : {
    dataPrepare(dataSet)
    {
      if(dataSet == "customer"){
        this.actionName = "getCustomers";
        this.search = this.$store.getters.getCustomerSearch;
        this.searchType = this.$store.getters.getCustomerSearchType;
      }
      else if(dataSet == "order"){
        this.actionName = "getOrders";
        this.search = this.$store.getters.getOrderSearch;
        this.searchType = this.$store.getters.getOrderSearchType;
      }else if(dataSet == "product"){
        this.actionName = "getProducts";
        this.search = this.$store.getters.getProductSearch;
        this.searchType = this.$store.getters.getProductSearch;
      }
    },
    getDatas(pageData){
      this.$store.dispatch(this.actionName, pageData);
    },
    makePagination(meta){
        let currentPage = parseInt(meta.current_page);
        let lastPage = parseInt(meta.last_page);
        let pages = [];

        if(lastPage <= 12)
        {
            for(var i = 1; i <= lastPage; i++)
            {
                pages.push(i);
            }
        }
        else
        {
            pages.push(1);
            pages.push(2);
            pages.push(3);
            if(currentPage > 6 || currentPage == lastPage || currentPage == 1)
            {
                pages.push("...");
            }


            let start, end;

            if(currentPage > 2)
            {
                start = currentPage-2 > 0 ? currentPage-2 : 1;
                end = currentPage+2 <= lastPage ? currentPage+2 : lastPage;
            }

            if(currentPage == lastPage || currentPage == 1)
            {
                start = parseInt(lastPage/2)-2 > 0 ? parseInt(lastPage/2)-2 : 1;
                end = parseInt(lastPage/2)+2 <= lastPage ? parseInt(lastPage/2)+2 : lastPage;
            }

            for(i = start; i <= end; i++)
            {
                if(pages.indexOf(i) == -1)
                {
                    pages.push(i);
                }
            }

            if(currentPage < lastPage-5 || currentPage == lastPage || currentPage == 1)
            {
                pages.push("...");
            }

            start = lastPage-2 > 0 ? lastPage-2 : lastPage;
            end = lastPage;

            for(i = start; i <= end; i++)
            {
                if(pages.indexOf(i) == -1)
                {
                    pages.push(i);
                }
            }


        }

        this.pagination = {
            pages : pages,
            current_page: currentPage,
            last_page: lastPage,
            next_page: currentPage < lastPage ? (currentPage+1) : null,
            prev_page: currentPage > 1 ? (currentPage-1) : null
        };
    }
  }
}
</script>

<style scope>
ul.pagination > li.active > a
{
    color: white;
    background-color: #6c757d !Important;
    border: solid 1px #6c757d !Important;
}

ul.pagination > li > a
{
    color: #6c757d;
}

</style>
