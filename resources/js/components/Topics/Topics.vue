<template>
    <div class="container-fluid">
        <h2 class="text-muted text-center mt-3 mb-2">
            Debates ({{ pagination.total }})
        </h2>
        <div class="card mb-3">
            <div class="card-body">
                <topics-table>
                    <template slot="topics-thead">
                        <topics-thead />
                    </template>
                    <template slot="topics-tbody">
                       <topics-tbody v-if="topics.length">
                            <topics-row 
                                v-for="topic in topics"
                                :topic="topic"
                                :key="topic.id"
                            />                          
                       </topics-tbody>
                       <topics-tbody v-else>
                           <tr>
                               <td colspan="6">
                                   <div class="empty-results">
                                       No hay ningún debate todavía, ¡Sé el primero!
                                   </div>
                               </td>
                           </tr>
                       </topics-tbody>
                    </template>
                </topics-table>
            </div>
                <div class="card-footer">
                    <topics-pagination :pagination="pagination" />
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="mb-3 txt-center">
                        ¿Tienes alguna duda?
                    </h2>
                </div>
                <div class="card-body">
                    <topics-form :course="course" />
                </div>
            </div>
        </div>    
</template>

<script>
    import TopicsTable from "./TopicsTable";
    import TopicsThead from "./TopicsThead";
    import TopicsTbody from "./TopicsTbody";
    import TopicsRow from "./TopicsRow";
    import TopicsPagination from "./TopicsPagination";
import TopicsForm from './TopicsForm.vue';
    export default {
        name: "Topics",
        components: {TopicsPagination, TopicsRow, TopicsTbody, TopicsThead, TopicsTable, TopicsForm},
        props: {
            course: {
                type: String,
                required: true
            }
        },
        data() {
            return {
                topics: [],
                page: 1,
                pagination: {
                    current_page: 1,
                    total: 0,
                    per_page: 10
                }
            }
        },
        async mounted() {
            await this.fetchTopics();
            this.$learningBus.$on("topics:pagination:fired", async (page) => {
                this.page = page;
                await this.fetchTopics();
            });
            // para cuando se cree un nuevo debate
            this.$learningBus.$on("topics:new", async () => {
                this.page = 1;
                await this.fetchTopics();
            })
        },
        methods: {
            async fetchTopics() {
                const { data: { data: topics, meta: pagination } } = await window.axios
                    .get(`/courses/${this.course}/topics/json?page=${this.page}`);
                this.topics = topics;
                this.pagination = pagination;
            }
        }
    }
</script>