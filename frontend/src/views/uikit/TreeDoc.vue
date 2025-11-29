<script setup lang="ts">
import { NodeService } from '@/service/NodeService';
import { onMounted, ref } from 'vue';
import type { TreeNode } from 'primevue/treenode';

const treeValue = ref<TreeNode[] | null>(null);
const selectedTreeValue = ref<{ [key: string]: boolean } | null>(null);

const treeTableValue = ref<TreeNode[] | null>(null);
const selectedTreeTableValue = ref<{ [key: string]: boolean } | null>(null);

onMounted(() => {
    NodeService.getTreeNodes().then((data: TreeNode[]) => {
        treeValue.value = data;
    });

    NodeService.getTreeTableNodes().then((data: TreeNode[]) => {
        treeTableValue.value = data;
    });
});
</script>

<template>
    <div class="card">
        <div class="font-semibold text-xl">Tree</div>
        <Tree :value="treeValue" selectionMode="checkbox" v-model:selectionKeys="selectedTreeValue"></Tree>
    </div>

    <div class="card">
        <div class="font-semibold text-xl mb-4">TreeTable</div>
        <TreeTable :value="treeTableValue" selectionMode="checkbox" v-model:selectionKeys="selectedTreeTableValue">
            <Column field="name" header="Name" :expander="true"></Column>
            <Column field="size" header="Size"></Column>
            <Column field="type" header="Type"></Column>
        </TreeTable>
    </div>
</template>
