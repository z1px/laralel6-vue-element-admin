<template>
    <div class="app-container">
        <div v-if="manageInfo">
            <el-row :gutter="20">

                <el-col :span="6" :xs="24">
                    <user-card v-model="manageInfo"/>
                </el-col>

                <el-col :span="18" :xs="24">
                    <el-card>
                        <account v-model="manageInfo"/>
                    </el-card>
                </el-col>

            </el-row>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import UserCard from './components/UserCard';
    import Account from './components/Account';

    export default {
        name: 'Profile',
        components: {UserCard, Account},
        computed: {
            ...mapGetters([
                'manageInfo'
            ])
        }
    }
</script>
