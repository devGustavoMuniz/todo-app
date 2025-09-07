export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
}

export interface Task {
    id: number;
    title: string;
    description?: string;
    status: 'pending' | 'in_progress' | 'done';
    status_label: string;
    user_id: number;
    created_at: string;
    updated_at: string;
    is_completed: boolean;
}

export interface TaskStats {
    total: number;
    pending: number;
    in_progress: number;
    done: number;
    completion_rate: number;
}

export interface PageProps {
    auth: {
        user: User;
    };
    flash: {
        message?: string;
        error?: string;
    };
}

export interface PaginatedData<T> {
    data: T[];
    links: {
        first: string;
        last: string;
        prev: string | null;
        next: string | null;
    };
    meta: {
        current_page: number;
        from: number;
        last_page: number;
        per_page: number;
        to: number;
        total: number;
    };
}