<template>
    <AdminLayout>
        <div class="header">
            <h1>Payment #{{ payment.id }}</h1>
            <Link href="/admin/payments" class="back-link">Back to List</Link>
        </div>

        <div class="details-card">
            <div class="details-grid">
                <strong>Tenant:</strong>
                <Link v-if="payment.tenant_name" :href="`/admin/tenants/${payment.tenant_id}`" class="link">{{ payment.tenant_name }}</Link>
                <span v-else>—</span>

                <strong>Property:</strong>
                <Link :href="`/admin/properties/${payment.property_id}`" class="link">{{ payment.property_name }}</Link>

                <strong>Amount:</strong>
                <span class="highlight">${{ formatMoney(payment.amount) }}</span>

                <strong>Type:</strong>
                <span>{{ capitalize(payment.type) }}</span>

                <strong>Status:</strong>
                <span class="badge" :style="{ backgroundColor: getColor(payment.status_color) }">{{ payment.status_label }}</span>

                <strong>Due Date:</strong>
                <span>{{ payment.due_date }}</span>

                <strong>Paid On:</strong>
                <span>{{ payment.paid_at || '—' }}</span>

                <strong v-if="payment.notes">Notes:</strong>
                <p v-if="payment.notes" class="notes">{{ payment.notes }}</p>
            </div>

            <div class="actions-section">
                <button
                    v-if="payment.status !== 'paid' && payment.status !== 'refunded'"
                    @click="markPaid"
                    class="btn btn-green"
                    :disabled="processing"
                >
                    Mark as Paid
                </button>
                <button
                    v-if="payment.status === 'paid'"
                    @click="markRefunded"
                    class="btn btn-danger"
                    :disabled="processing"
                >
                    Refund
                </button>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ payment: Object });
const processing = ref(false);

const capitalize = (s) => s.charAt(0).toUpperCase() + s.slice(1);
const formatMoney = (c) => (c / 100).toFixed(2);
const getColor = (c) => ({ orange: '#f59e0b', green: '#10b981', red: '#ef4444', gray: '#6b7280' }[c] || '#6b7280');

const markPaid = () => {
    processing.value = true;
    router.post(`/admin/payments/${props.payment.id}/paid`, {}, { onFinish: () => { processing.value = false; } });
};

const markRefunded = () => {
    processing.value = true;
    router.post(`/admin/payments/${props.payment.id}/refund`, {}, { onFinish: () => { processing.value = false; } });
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
.highlight { font-weight: 700; color: #2779bd; font-size: 18px; }
.notes { margin: 0; line-height: 1.5; }
.link { color: #3490dc; text-decoration: none; }
.link:hover { text-decoration: underline; }
.badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; color: white; }
.actions-section { margin-top: 25px; padding-top: 20px; border-top: 1px solid #ddd; display: flex; gap: 10px; }
.btn { padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; color: white; }
.btn:hover:not(:disabled) { opacity: .9; }
.btn:disabled { opacity: .5; cursor: not-allowed; }
.btn-green { background: #10b981; }
.btn-danger { background: #e3342f; }
</style>
