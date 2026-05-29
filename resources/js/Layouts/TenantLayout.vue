<template>
    <div class="tenant-layout">
        <nav class="nav">
            <span class="brand">RentalFlow</span>
            <div class="nav-links">
                <Link href="/dashboard">My Bookings</Link>
                <Link href="/maintenance">Maintenance</Link>
                <Link href="/logout" method="post" as="button" class="btn-logout">Logout</Link>
            </div>
        </nav>

        <div v-if="$page.props.flash.success" class="success">{{ $page.props.flash.success }}</div>
        <div v-if="$page.props.flash.error" class="error">{{ $page.props.flash.error }}</div>

        <main>
            <slot />
        </main>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<style scoped>
.tenant-layout { max-width: 1000px; margin: 0 auto; padding: 20px; font-family: system-ui, -apple-system, sans-serif; }
.nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 10px; border-bottom: 2px solid #ddd; }
.brand { font-weight: 700; font-size: 18px; color: #3490dc; }
.nav-links { display: flex; gap: 20px; align-items: center; }
.nav-links a { color: #3490dc; text-decoration: none; }
.nav-links a:hover { text-decoration: underline; }
.btn-logout { background: none; border: none; cursor: pointer; color: #e3342f; font-size: inherit; font-family: inherit; padding: 0; }
.btn-logout:hover { text-decoration: underline; }
.success { padding: 12px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 20px; }
.error { padding: 12px; background: #f8d7da; color: #721c24; border-radius: 4px; margin-bottom: 20px; }
main { min-height: 500px; }
</style>
