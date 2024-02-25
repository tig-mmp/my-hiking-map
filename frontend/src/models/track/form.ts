import { LandmarkForm } from "@/models/landmark/form";
import { PointForm } from "@/models/point/form";
import { FileForm } from "../file/form";

export interface TrackForm {
  id?: number;
  name?: string;
  file?: FileForm;
  startDistrictId?: number | string;
  startCountyId?: number | string;
  startLocationId?: number | string;
  description?: string;
  distance?: number;
  slope?: number;
  routeCode?: string;
  difficulty?: number;
  landscape?: number;
  enjoyment?: number;
  trackUrl?: string;
  officialUrl?: string;
  groupName?: string;
  guide?: string;
  weekNumber?: number;
  isMoita?: boolean;
  duration?: string;
  date?: Date;
  startTime?: string;
  endTime?: string;
  landmarks?: LandmarkForm[];
  points?: PointForm[];
}

const trackFormDataType = "form";
export { trackFormDataType };
