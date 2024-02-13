import { LandmarkForm } from "@/models/landmark/form";

export interface TrackForm {
  name?: string;
  url?: string;
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
  startedAt?: string;
  endedAt?: string;
  landmarks?: LandmarkForm[];
}

const trackFormDataType = "form";
export { trackFormDataType };
