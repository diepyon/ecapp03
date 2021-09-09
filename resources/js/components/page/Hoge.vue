<template>
    <v-app class="catalog_top">
        <div class="title-section container">
            <span class="system-bar-title">カタログの追加/削除</span>
        </div>
        <div class="card-section">
            <div v-show="isLoading"><Spinner /></div>
            <div v-show="!isLoading" class="input-field">
                <v-container>
                    <v-row>
                        <v-col cols="8" sm="5" md="5" lg="5" xl="5" >
                            <v-text-field
                                v-model="form.title"
                                label="タイトル"
                                placeholder="2021年5月3回のカタログ"
                                solo
                        ></v-text-field>
                        <div v-show="this.validationError.title" class="error-message">
                            {{this.validationError.title}}
                        </div>
                        </v-col>
                        <v-col cols="8" sm="5" md="5" lg="5" xl="5" >
                            <v-text-field
                                v-model="form.display_id"
                                label="企画回ID"
                                placeholder="20210501"
                                solo
                        ></v-text-field>
                        <div v-show="this.validationError.display_id" class="error-message">
                            {{this.validationError.display_id}}
                        </div>
                        </v-col>
                    </v-row>
                    <v-card class="card-body">
                        <v-container class="flex-column">
                            <v-row justify="center" align-content="center">
                                <div v-show="!Object.keys(this.form.articles).length"
                                     class="no-contents-text">
                                    登録されたカタログはありません。
                                </div>
                                <v-col v-for="(article, index) in this.form.articles" :key="index" cols=12>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title>{{article.article_name}}</v-list-item-title>
                                        </v-list-item-content>

                                        <v-list-item-action @click="removePdfButton(index)" class="minus-button">
                                            <v-icon color="brown">mdi-minus-circle-outline</v-icon>
                                        </v-list-item-action>
                                    </v-list-item>
                                    <v-divider></v-divider>
                                </v-col>

                            </v-row>

                            <v-row justify="center" align-content="center" class="mt-10 mb-1">
                                <span class="button-upper-title">カタログを追加</span>
                                <input id="add-pdf" type="file" ref="file" accept=".pdf" multiple="multiple"
                                       style="display:none;" @change="fileSelected" >
                            </v-row>

                            <v-row justify="center" align-content="center">
                                <v-btn class="mx-2" fab dark color="brown" @click="addPdfButton">
                                    <v-icon dark>mdi-plus</v-icon>
                                </v-btn>
                            </v-row>
                        </v-container>
                    </v-card>
                    <v-row justify="center" align-content="center" class="mt-8">
                        <div class="text-center">
                            <v-dialog v-model="dialog" width="700">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                        class="brown white--text my-5"
                                        elevation="2"
                                        width="200px"
                                        height="50px"
                                        v-bind="attrs"
                                        v-on="on"
                                    >
                                        投稿を{{newPost ? '完了' : '更新'}}する
                                    </v-btn>
                                </template>
                                <v-card>
                                    <div class="modal-text">
                                        投稿を{{newPost ? '完了' : '更新'}}します。よろしいですか？
                                    </div>
                                    <div class="modal-button-group">
                                        <v-card-actions>
                                            <v-btn
                                                class="grey lighten-1 white--text my-5 mx-4"
                                                width="200px"
                                                height="50px"
                                                padding="10px"
                                                @click="dialog = false"
                                            >
                                                キャンセル
                                            </v-btn>

                                            <v-btn
                                                class="red darken-4 white--text my-5 mx-4"
                                                width="200px"
                                                height="50px"
                                                @click="submitForm() & (dialog = false)"
                                            >
                                                OK
                                            </v-btn>
                                        </v-card-actions>
                                    </div>
                                </v-card>
                            </v-dialog>
                        </div>
                    </v-row>
                    <v-row justify="center" align-content="center" class="delete-link-text">
                        <a href="/admin/top" class="link-text">戻る</a>
                    </v-row>
                    <v-row justify="end" align-content="center" class="delete-link-text"
                           @click="deleteCatalog">
                        この企画自体を削除する
                    </v-row>
                </v-container>
            </div>
        </div>
    </v-app>
</template>

<script>
import Spinner from './Spinner'
export default {
  components: { Spinner },
  props: { catalog : [Object, String] },
  data() {
    return {
      newPost: true,
      dialog : false,
      isLoading: false,
      catalogId: '',
      form: {
        'title': '',
        'display_id': '',
        'articles': []
      },
      fileInfo: [],
      updateFileInfo: [],
	    deleteFileInfo: [],
      validationError: {
        'title': '',
        'display_id': '',
      },
    };
  },
  mounted() {
    // アップデート時はpropsよりデータを取得
    if(this.catalog) {
      this.newPost = false
      this.catalogId = this.catalog.data.catalog_id
      this.form.title = this.catalog.data.attributes.catalog_title
      this.form.display_id = this.catalog.data.attributes.display_id
      this.form.articles = [...this.catalog.data.attributes.articles]
	    this.fileInfo = [...this.catalog.data.attributes.articles]
    }
  },
  methods: {
    addPdfButton: function () {
      document.getElementById("add-pdf").click()
    },
    removePdfButton: function (index) {
      this.form.articles.splice(index, 1)
      let deleteFile = this.fileInfo.splice(index, 1)
      if (deleteFile[0].id) {
        this.deleteFileInfo = [...this.deleteFileInfo, {
          'id': deleteFile[0].id,
          'article_name': deleteFile[0].article_name,
          'catalog_id': deleteFile[0].catalog_id,
        }]
      }
      this.form.articles = [...this.form.articles]
      this.fileInfo = [...this.fileInfo];
    },
    fileSelected(event){
      Object.keys(event.target.files).map(index => {
        this.fileInfo = [...this.fileInfo, event.target.files[index]]
        this.form.articles = [...this.form.articles, {
          'article_name':  event.target.files[index].name,
          'catalog_id': this.catalogId,
          'file_path': '',
        }]
      })
    },
    submitForm: function () {
      this.checkForm()
      // バリデーション通過時にフォームをPOST
      if (!this.validationError.title && !this.validationError.display_id) {
        // 新規投稿
        this.isLoading = true
        if (this.newPost) {
          // POSTするフォームデータの作成
          let formData = new FormData()
          for( let i= 0; i < this.fileInfo.length; i++ ){
            let file= this.fileInfo[i];
            formData.append('files[' + i + ']', file)
          }
          formData.append('form[title]',this.form.title)
          formData.append('form[display_id]',this.form.display_id)
          axios.post('/admin/post', formData)
            .then(response => {
              this.dialog = false
              this.isLoading = false
              alert('カタログの登録が完了しました')
              window.location.href = "/admin/top"
            })
            .catch(error => {
              this.isLoading = false
              console.log(error)
            })
        // 編集投稿
        } else {
          // POSTするフォームデータの作成
          let formData = new FormData()
          let fileIndex = 0
          for( let i= 0; i < this.fileInfo.length; i++ ){
            let file= this.fileInfo[i];
            if(!this.fileInfo[i].id) {
              formData.append('files[' + fileIndex + ']', file)
              fileIndex++
            }
          }
          for( let i= 0; i < this.deleteFileInfo.length; i++ ){
            let deleteFileObject= JSON.stringify(this.deleteFileInfo[i]);
            formData.append('deleteFiles[' + i + ']', deleteFileObject)
          }
          formData.append('form[title]',this.form.title)
          formData.append('form[display_id]',this.form.display_id)
          axios.post(`/admin/post/${this.catalogId}`, formData)
            .then(response => {
              this.dialog = false
              this.isLoading = false
              alert('カタログの更新が完了しました')
              window.location.href = "/admin/top"
            })
            .catch(error => {
              this.isLoading = false
              console.log(error)
            })
        }
      }
    },
    checkForm: function () {
      //タイトル必須入力
      if (!this.form.title) {
        this.validationError.title = 'カタログのタイトルを入力してください'
      }
      // 企画回ID必須入力
      if (!this.form.display_id) {
        this.validationError.display_id = '企画回IDを入力してください'
      }
      // 企画回IDが数値のみかどうか
      let regexp = new RegExp(/^[0-9]+(\.[0-9]+)?$/)
      if(this.form.display_id && !regexp.test(this.form.display_id)) {
         this.validationError.display_id = '企画回IDは半角数字のみで入力してください'
      }
    },
    clearValidateError: function () {
      this.validationError.title = ""
      this.validationError.display_id = ""
    },
    deleteCatalog: function () {
      this.isLoading = true
      if (!this.newPost) {
        axios.delete(`/admin/delete/${this.catalogId}`)
          .then(response => {
            alert('カタログの削除が完了しました')
            this.isLoading = false
            window.location.href = "/admin/top"
          })
          .catch(error => {
            this.isLoading = false
            console.log(error)
          })
      } else {
          window.location.href = "/admin/top";
      }
    },
  },
  watch: {
    form: {
      handler: function () {
        this.clearValidateError()
      },
      deep: true
    },
  },
}
</script>