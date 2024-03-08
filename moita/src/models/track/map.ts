import { LandmarkMap } from "@/models/landmark/map";
import { Position } from "vue-maplibre-gl";

export interface TrackMap {
  id?: number;
  name?: string;
  description?: string;
  date?: Date;
  landmarks?: LandmarkMap[];
  points?: number[];
}

const trackMapDataType = "map";
export { trackMapDataType };
