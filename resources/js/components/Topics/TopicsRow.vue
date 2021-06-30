<template>
    <tr>
        <td>{{ topic.id }}</td>
        <td>{{ topic.title }}</td>
        <td>{{ topic.owner.name }}</td>
        <td>{{ topic.created_at }}</td>
        <td>
            <span v-if="topic.status === 'PENDING'" class="badge badge-warning">
                PENDIENTE
            </span>
            <span v-else class="badge badge-success">
                SOLUCIONADO
            </span>
        </td>
        <td>Acciones</td>
    </tr>
</template>

<script>
    export default {
        name: "TopicsRow",
        props: {
            topic: {
                type: Object,
                required: true
            }
        }
    }
</script>