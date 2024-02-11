export interface UserForm {
  id: number | null;
  username?: string;
  role?: string;
  password?: string;
}

const userFormDataType = "form";
export { userFormDataType };
