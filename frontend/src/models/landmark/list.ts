import { FileForm } from "@/models/file/form";

export interface LandmarkList {
  id?: number;
  name?: string;
  file?: FileForm;
}

const landmarkListDataType = "list";
export { landmarkListDataType };
