<script setup>
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { computed } from 'vue';

const props = defineProps({
    modelValue: { type: String, default: '' },
    placeholder: { type: String, default: 'Write your article...' },
});

const emit = defineEmits(['update:modelValue']);

const content = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val ?? ''),
});

const toolbar = [
    [{ header: [1, 2, 3, false] }],
    ['bold', 'italic', 'underline'],
    [{ list: 'ordered' }, { list: 'bullet' }],
    ['blockquote', 'link', 'image'],
    ['clean'],
];
</script>

<template>
    <div class="rich-text-editor rounded-xl border border-gray-200 overflow-hidden bg-white">
        <QuillEditor
            v-model:content="content"
            content-type="html"
            theme="snow"
            :toolbar="toolbar"
            :placeholder="placeholder"
            class="min-h-[280px]"
        />
    </div>
</template>

<style>
.rich-text-editor .ql-toolbar {
    border: none !important;
    border-bottom: 1px solid #e5e7eb !important;
    background: #f9fafb;
}
.rich-text-editor .ql-container {
    border: none !important;
    font-family: inherit;
    font-size: 15px;
    min-height: 280px;
}
.rich-text-editor .ql-editor {
    min-height: 280px;
}
</style>
