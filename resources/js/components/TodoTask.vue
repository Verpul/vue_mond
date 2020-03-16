<template>
  <ul class="todo-list">
    <li v-for="todo in todos.data" :key="todo.id">
      <div class="row m-0">
        <div class="col col-md-auto p-0">
          <!-- drag handle -->
          <span class="handle ">
            <i class="fas fa-ellipsis-v"></i>
            <i class="fas fa-ellipsis-v"></i>
          </span>
          <span style="cursor: pointer" @click="slideSteps(todo.id)">
            <i class="fas fa-chevron-circle-right"></i>
          </span>
          <!-- checkbox -->
          <div class="icheck-primary d-inline ml-1" v-if="todo.active">
            <input type="checkbox" value="" 
              :name="'todo' + todo.id" 
              :id="'todoCheck' + todo.id"
              @click.prevent="completeTodo(todo.id)">
            <label :for="'todoCheck' + todo.id"></label>
          </div>
          <!-- Emphasis label -->
          <small class="badge text-white ml-0" :class="badgeColor(todo.due_date)" v-if="todo.active">
            <i class="far fa-clock"></i>
          </small>
          <small>{{formatDate(todo.due_date)}}</small> 
          <small class="badge badge-dark" v-if="!todo.active">
            <i class="far fa-clock"></i> Завершена 
          </small>
        </div>
        <div class="col">
          <!-- todo title -->
          <span class="text" :class="todo.active ? '' : 'text-muted'">{{ todo.title }}:</span>
          <!-- General tools such as edit or delete-->
          <div class="tools">
            <i class="fas fa-edit" @click="openEditForm(todo)"></i>
            <i class="fas fa-trash" @click="deleteTodo(todo.id)"></i>
          </div>
          <!-- todo text -->
          <span class="text" :class="todo.active ? '' : 'text-muted'">{{ todo.task }}</span>
        </div>
      </div>
    </li>
  </ul>
</template>

<script>

  export default{
    methods: {
      formatDate(date){
        if(date){
          let newDate = this.$moment(date).format('DD.MM.YYYY HH:mm');
          let dueIn = this.$moment(date).from();
          return ' До '+ newDate+ ' (' + dueIn +  ')';
        }
        return ' Бессрочно';    
      },
      badgeColor(date){
        if(date){
          var dueDate = this.$moment(date);
          var dateNow = this.$moment();
          var hoursUntilDue = dueDate.diff(dateNow, 'hours');
          
          if(hoursUntilDue < 1){
            return ' red'
          }else if(hoursUntilDue <= 7){
            return 'orange'
          }else if(hoursUntilDue <= 24){
            return 'text-dark yellow'
          }else if(hoursUntilDue <= 168){
            return 'green'
          }else{
            return 'teal'
          }
        }
        return 'cyan';
      },
      completeTodo(id){
        this.$swal({
          title: 'Вы уверены?',
          text: "Задача будет завершена!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Да, завершить!',
          cancelButtonText: 'Отмена'
          }).then((result) => {
              if (result.value) {
                  //Send update request
                  axios.put('/api/todo/active/'+id).then(() => {
                      //reload data table
                      this.$emit('loadTodos');

                      this.$swal({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        icon: 'success',
                        title: 'Задача выполнена!'
                      });   
                  }).catch(() => {
                      this.$swal(
                          'Действие отменено',
                          'Что-то пошло не так',
                          'error'
                      )
                  });
              }       
        })
      },
      deleteTodo(id){
        this.$swal({
          title: 'Вы уверены?',
          text: "Задача будет удалена!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Да, удалить!',
          cancelButtonText: 'Отмена'
          }).then((result) => {
              if (result.value) {
                  //Send delete request
                  axios.delete('api/todo/' + id).then(() => {
                      //reload data table
                      this.$emit('loadTodos');

                      this.$swal({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        icon: 'success',
                        title: 'Задача удалена!'
                      });   
                  }).catch(() => {
                      this.$swal(
                          'Действие отменено',
                          'Что-то пошло не так',
                          'error'
                      )
                  });
              }       
        })
      },
      openEditForm(todo){
        this.$emit('openEdit', todo);
      },
      slideSteps(id){

      }
    },
    props: [
      'todos',
    ]
  }
</script>

<style>
  
</style>


