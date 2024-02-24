import { LandmarkForm } from "@/models/landmark/form";
import { PointForm } from "@/models/point/form";

export interface TrackForm {
  id?: number;
  name?: string;
  fileUrl?: string;
  startDistrictId?: number;
  startCountyId?: number;
  startLocationId?: number;
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
