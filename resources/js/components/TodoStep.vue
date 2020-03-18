<template>
  <div>
    <hr class="mb-1 mt-1">
    <ul class="pl-4">
      <li v-for="step in steps" :key="step.id">{{step.step}}
        <div class="tools">
          <i class="fas fa-edit c-orange"></i>
          <i class="fas fa-trash c-orange" @click="deleteStep(step.id, step.todo_id)"></i>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
  export default {
    methods: {
      deleteStep(id, todoId){
        this.$swal({
          title: 'Вы уверены?',
          text: 'Данный пункт будет удален',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Да, удалить!',
          cancelButtonText: 'Отмена'
          }).then((result) => {
              if (result.value) {
                  //Send delete request
                  axios.delete('api/todo/step/' + id).then(() => {
                      //load changed steps
                      this.loadSteps(todoId);

                      this.$swal({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        icon: 'success',
                        title: 'Пункт удален!'
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
      loadSteps(todoId){
        axios.get('api/todo/step', {
          params: {
            todoId: todoId
          }
        }).then((result) => {
          this.steps = result.data;
        })
      }
    },
    props: [
      'steps'
    ]
  }
</script>

<style scoped>
  .c-orange {
    color: #f6993f;
  }
</style>



