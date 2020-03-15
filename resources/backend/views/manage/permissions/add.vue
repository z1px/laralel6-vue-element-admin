<template>
    <edit-info :is-update="false"/>
</template>

<script>
    import EditInfo from './components/EditInfo'

    export default {
        name: 'PermissionsAdd',
        components: {EditInfo}
    }
</script>

