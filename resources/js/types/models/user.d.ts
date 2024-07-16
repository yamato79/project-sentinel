declare type UserModel = {
    user_id: number;
    email: string;
    name: string;
    can_view?: boolean;
    can_edit?: boolean;
    joined_at?: string;
}
