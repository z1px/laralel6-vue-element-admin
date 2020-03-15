<template>
    <edit-info :is-update="true"/>
</template>

<script>
    import EditInfo from './components/EditInfo'

    export default {
        name: 'PermissionsUpdate',
        components: {EditInfo}
    }
</script>

