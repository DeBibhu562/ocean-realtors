<script setup>
import { computed, defineAsyncComponent, onMounted } from 'vue';

const QuillEditor = defineAsyncComponent(() =>
    import('@vueup/vue-quill').then((module) => module.QuillEditor),
);

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

onMounted(() => {
    import('@vueup/vue-quill/dist/vue-quill.snow.css');
});
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
    color: #111827;
}

.rich-text-editor .ql-editor h1 {
    font-size: 1.5rem;
    font-weight: 800;
    color: #0f172a;
    margin: 1rem 0 0.5rem;
}

.rich-text-editor .ql-editor h2 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0f172a;
    margin: 1rem 0 0.5rem;
}

.rich-text-editor .ql-editor h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #0f172a;
}

.rich-text-editor .ql-editor p {
    margin: 0.5rem 0;
    line-height: 1.65;
}

.rich-text-editor .ql-editor ul,
.rich-text-editor .ql-editor ol {
    padding-left: 1.25rem;
    margin: 0.5rem 0;
}

.rich-text-editor .ql-editor li {
    margin: 0.25rem 0;
}
</style>
