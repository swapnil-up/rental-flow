<template>
    <div class="empty-state">
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
        </svg>
        <h3>{{ title }}</h3>
        <p v-if="description">{{ description }}</p>
        <slot />
    </div>
</template>

<script setup>
defineProps({
    title: String,
    description: String,
});
</script>

<style scoped>
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: #f9fafb;
    border-radius: 12px;
    border: 2px dashed #d1d5db;
}
.icon {
    width: 48px;
    height: 48px;
    color: #9ca3af;
    margin-bottom: 16px;
}
h3 {
    margin: 0 0 8px;
    font-size: 18px;
    font-weight: 600;
    color: #374151;
}
p {
    margin: 0;
    font-size: 14px;
    color: #6b7280;
}
</style>
