import { z } from 'zod';

export const TaskSchema = z.object({
    title: z.string()
        .min(1, 'Título é obrigatório')
        .max(255, 'Título muito longo'),
    description: z.string()
        .max(5000, 'Descrição muito longa')
        .optional()
        .or(z.literal('')),
    status: z.enum(['pending', 'in_progress', 'done'])
        .default('pending'),
});

export const TaskFilterSchema = z.object({
    status: z.enum(['pending', 'in_progress', 'done', ''])
        .optional(),
    search: z.string()
        .optional(),
});

export const UpdateTaskStatusSchema = z.object({
    status: z.enum(['pending', 'in_progress', 'done']),
});

export type TaskInput = z.infer<typeof TaskSchema>;
export type TaskFilter = z.infer<typeof TaskFilterSchema>;
export type UpdateTaskStatusInput = z.infer<typeof UpdateTaskStatusSchema>;

// Validation helpers
export function validateTask(data: unknown): TaskInput {
    return TaskSchema.parse(data);
}

export function validateTaskFilters(data: unknown): TaskFilter {
    return TaskFilterSchema.parse(data);
}