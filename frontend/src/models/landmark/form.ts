import { PointForm } from "@/models/point/form";
import { FileForm } from "@/models/file/form";

export interface LandmarkForm {
  id?: number;
  name?: string;
  landmarkTypeId?: number;
  file?: FileForm | null;
  point?: PointForm;
}

const landmarkFormDataType = "form";
export { landmarkFormDataType };
