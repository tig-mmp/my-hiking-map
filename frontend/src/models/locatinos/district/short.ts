import { StateShort } from "@/models/locations/state/short";

export interface DistrictShort {
  id: number;
  name: string;
  states: StateShort[];
}

const districtShortDataType = "short";
export { districtShortDataType };
