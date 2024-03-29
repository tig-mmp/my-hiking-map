import { PointMap } from "@/models/point/map";
import { FileMap } from "@/models/file/map";

export interface LandmarkMap {
  id?: number;
  name?: string;
  landmarkTypeName?: string;
  file?: FileMap | null;
  point?: PointMap;
}

const landmarkMapDataType = "map";
export { landmarkMapDataType };
