<template>
    <AdminLayout>
        <div class="header">
            <h1>{{ request.title }}</h1>
            <Link href="/admin/maintenance" class="back-link">Back to List</Link>
        </div>

        <div class="details-card">
            <div class="details-grid">
                <strong>Property:</strong>
                <Link :href="`/admin/properties/${request.property_id}`" class="link">{{ request.property_name }}</Link>

                <strong>Tenant:</strong>
                <span>{{ request.tenant_name || '—' }}</span>

                <strong>Priority:</strong>
                <span class="priority" :class="request.priority">{{ capitalize(request.priority) }}</span>

                <strong>Status:</strong>
                <span class="badge" :style="{ backgroundColor: getColor(request.status_color) }">{{ request.status_label }}</span>

                <strong>Created:</strong>
                <span>{{ request.created_at }}</span>

                <strong>Description:</strong>
                <p class="description">{{ request.description }}</p>
            </div>

            <div v-if="request.available_transitions.length > 0" class="actions-section">
                <h4>Update Status</h4>
                <div class="transition-buttons">
                    <button
                        v-for="t in request.available_transitions"
                        :key="t.value"
                        @click="transition(t.value)"
                        class="btn"
                        :disabled="processing"
                    >
                        Mark as {{ t.label }}
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ request: Object });
const processing = ref(false);

const capitalize = (s) => s.charAt(0).toUpperCase() + s.slice(1);
const getColor = (c) => ({ red: '#ef4444', orange: '#f59e0b', blue: '#3b82f6', green: '#10b981' }[c] || '#6b7280');

const transition = (status) => {
    processing.value = true;
    router.post(`/admin/maintenance/${props.request.id}/transition`, { status }, {
        onFinish: () => { processing.value = false; },
    });
};
</script>

<style scoped>
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
h1 { margin: 0; font-size: 20px; }
.back-link { color: #3490dc; text-decoration: none; }
.back-link:hover { text-decoration: underline; }
.details-card { background: #f7fafc; padding: 20px; border-radius: 8px; }
.details-grid { display: grid; grid-template-columns: 120px 1fr; gap: 10px; align-items: start; }
.details-grid strong { font-weight: 600; }
.description { margin: 0; line-height: 1.6; }
.link { color: #3490dc; text-decoration: none; }
.link:hover { text-decoration: underline; }
.badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; color: white; }
.priority { font-size: 12px; font-weight: 600; padding: 2px 8px; border-radius: 4px; }
.priority.low { background: #d1fae5; color: #065f46; }
.priority.medium { background: #fef3c7; color: #92400e; }
.priority.high { background: #fee2e2; color: #991b1b; }
.priority.emergency { background: #fce4ec; color: #b71c1c; font-weight: 700; }
.actions-section { margin-top: 25px; padding-top: 20px; border-top: 1px solid #ddd; }
.actions-section h4 { margin: 0 0 10px; }
.transition-buttons { display: flex; gap: 10px; flex-wrap: wrap; }
.btn { padding: 8px 16px; background: #3490dc; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
.btn:hover:not(:disabled) { background: #2779bd; }
.btn:disabled { opacity: .5; cursor: not-allowed; }
</style>
