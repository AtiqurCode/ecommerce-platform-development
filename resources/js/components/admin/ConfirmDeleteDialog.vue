<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { Trash2 } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';

withDefaults(
    defineProps<{
        title: string;
        description: string;
        action: {
            action: string;
            method: string;
        };
        label?: string;
    }>(),
    {
        label: 'Delete',
    },
);
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button variant="outline" size="sm" class="text-destructive hover:bg-destructive/10 hover:text-destructive">
                <Trash2 class="size-3.5" />
                {{ label }}
            </Button>
        </DialogTrigger>
        <DialogContent>
            <Form
                v-bind="action"
                :options="{ preserveScroll: true }"
                class="space-y-6"
                v-slot="{ processing }"
            >
                <DialogHeader class="space-y-3">
                    <DialogTitle>{{ title }}</DialogTitle>
                    <DialogDescription>{{ description }}</DialogDescription>
                </DialogHeader>
                <DialogFooter class="gap-2">
                    <DialogClose as-child>
                        <Button type="button" variant="secondary">Keep it</Button>
                    </DialogClose>
                    <Button type="submit" variant="destructive" :disabled="processing">
                        {{ processing ? 'Deleting...' : 'Yes, delete' }}
                    </Button>
                </DialogFooter>
            </Form>
        </DialogContent>
    </Dialog>
</template>
